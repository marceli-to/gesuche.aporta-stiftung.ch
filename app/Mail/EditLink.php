<?php
namespace App\Mail;
use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EditLink extends Mailable
{
  use Queueable, SerializesModels;

  public function __construct(
    protected Application $application,
    protected string $url,
    protected ?string $note = null,
  ) {}

  public function build()
  {
    return $this
                ->subject('Ihr Beitragsgesuch: Link zur Bearbeitung')
                ->with([
                  'application' => $this->application,
                  'url' => $this->url,
                  'note' => $this->note,
                  'expiresAt' => $this->application->edit_token_expires_at,
                ])
                ->markdown('mails.edit-link');
  }
}
