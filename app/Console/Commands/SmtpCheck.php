<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mime\Email;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\password;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

/**
 * Tests an arbitrary SMTP mailbox with credentials entered at the prompt.
 * Does not read or change the app's MAIL_* config, so it can check any mailbox.
 */
class SmtpCheck extends Command
{
  protected $signature = 'mail:smtp-check
    {--host= : SMTP host, e.g. smtp.office365.com}
    {--port= : SMTP port}
    {--encryption= : starttls, ssl or none}
    {--username= : SMTP username}
    {--from= : Sender address for the test mail}
    {--to= : Recipient address for the test mail}
    {--no-send : Only test connection and login, do not send a mail}';

  protected $description = 'Test an SMTP mailbox (connection, login, optional test mail) with prompted credentials';

  public function handle(): int
  {
    $host = $this->option('host') ?: text('SMTP host', default: 'smtp.office365.com', required: true);

    $encryption = $this->option('encryption') ?: select('Encryption', [
      'starttls' => 'STARTTLS (usually port 587)',
      'ssl'      => 'SSL/TLS (usually port 465)',
      'none'     => 'None (usually port 25)',
    ], default: 'starttls');

    $defaultPort = ['starttls' => '587', 'ssl' => '465', 'none' => '25'][$encryption] ?? '587';
    $port = (int) ($this->option('port') ?: text('Port', default: $defaultPort, required: true));

    $username = $this->option('username') ?? text('Username (empty for no login)', hint: 'Usually the full mailbox address');
    $password = $username !== '' ? password('Password', required: true) : '';

    $transport = new EsmtpTransport($host, $port, $encryption === 'ssl');
    if ($encryption === 'none') {
      $transport->setAutoTls(false);
    }
    if ($username !== '') {
      $transport->setUsername($username);
      $transport->setPassword($password);
    }

    // Step 1: connect, TLS handshake and login
    $this->line("Connecting to {$host}:{$port} ({$encryption})...");
    try {
      $transport->start();
    } catch (TransportExceptionInterface $e) {
      return $this->fail_with($e, $transport);
    }
    $this->info($username !== '' ? "✓ Connected and logged in as {$username}" : '✓ Connected (no login)');

    if ($this->option('no-send') || !confirm('Send a test mail?', default: true)) {
      $transport->stop();
      return self::SUCCESS;
    }

    // Step 2: send a test mail
    $from = $this->option('from') ?: text('From', default: $username, required: true);
    $to = $this->option('to') ?: text('To', required: true);

    $email = (new Email())
      ->from($from)
      ->to($to)
      ->subject('SMTP test ' . now()->format('Y-m-d H:i:s'))
      ->text("Test mail sent via {$host}:{$port} as " . ($username ?: 'anonymous') . ".\nIf you can read this, sending works.");

    try {
      $sent = $transport->send($email);
    } catch (TransportExceptionInterface $e) {
      return $this->fail_with($e, $transport);
    }
    $transport->stop();

    $this->info("✓ Test mail accepted by the server for {$to}");
    $this->line('  Message-ID: ' . $sent->getMessageId());
    $this->line('  Check the inbox (and spam folder) of the recipient.');
    return self::SUCCESS;
  }

  private function fail_with(TransportExceptionInterface $e, EsmtpTransport $transport): int
  {
    $this->error('✗ ' . $e->getMessage());

    $message = $e->getMessage();
    $hints = [
      '5.7.139' => 'M365: SMTP AUTH is disabled for this mailbox or tenant, or security defaults block it.',
      '5.7.3'   => 'M365: Authentication unsuccessful. Wrong password, SMTP AUTH disabled for the mailbox, or MFA/security defaults active.',
      '535'     => 'Login rejected: check username/password and whether SMTP AUTH is allowed for this mailbox.',
      '5.7.57'  => 'M365: client not authenticated to send. The login did not succeed.',
      '5.7.60'  => 'M365: the login user may not send as this From address. Use the mailbox address as From.',
      'Connection could not be established' => 'Host/port unreachable: check host, port, encryption and firewall (outgoing ports are often blocked on shared hosting).',
      'certificate' => 'TLS certificate problem: check the host name and encryption setting.',
    ];
    foreach ($hints as $needle => $hint) {
      if (str_contains($message, $needle)) {
        $this->warn('Hint: ' . $hint);
        break;
      }
    }

    $debug = trim($transport->getStream()->getDebug());
    if ($debug !== '' && confirm('Show SMTP conversation?', default: false)) {
      $this->line($this->mask_credentials($debug));
    }

    return self::FAILURE;
  }

  /**
   * The SMTP log contains the credentials in base64 (AUTH PLAIN/XOAUTH2 lines and the
   * client replies to "334" challenges). Lines look like "[timestamp] > AUTH LOGIN".
   */
  public static function mask_credentials(string $debug): string
  {
    $lines = preg_split('/\R/', $debug);
    $previous = '';
    foreach ($lines as $i => $line) {
      if (preg_match('/^(\[[^\]]*\] )?> AUTH (\S+) \S+/', $line, $m)) {
        $lines[$i] = "{$m[1]}> AUTH {$m[2]} [masked]";
      } elseif (preg_match('/^(\[[^\]]*\] )?> /', $line, $m) && preg_match('/^(\[[^\]]*\] )?< 334/', $previous)) {
        $lines[$i] = "{$m[1]}> [masked]";
      }
      $previous = $line;
    }
    return implode("\n", $lines);
  }
}
