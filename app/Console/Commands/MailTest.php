<?php
namespace App\Console\Commands;
use App\Models\Application;
use App\Models\MailQueue;
use Illuminate\Console\Command;

class MailTest extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'mail:process {--create}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Test the notifaction/confirmation mail';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return int
   */
  public function handle()
  {
    $create = $this->option('create');

    if ($create)
    {
      // Create dummy entries
      $application = Application::find(mt_rand(1,25));

      MailQueue::create([
        'type' => 'notification',
        'data' => $application->toJson()
      ]);
      $this->info('notification created for application id: ' . $application->id);

      MailQueue::create([
        'type' => 'confirmation',
        'data' => $application->toJson()
      ]);
      $this->info('confirmation created for application id: ' . $application->id);
    }

    // Process mail queue
    $mails = \App\Models\MailQueue::unprocessed()->get();
    $mails = collect($mails)->splice(0,2);

    $this->info('fetched mails...');

    foreach($mails->all() as $m)
    {
      $data = json_decode($m->data);

      try {

        // Notification to owner
        if ($m->type == 'notification') {
          \Mail::to(env('MAIL_TO'))->send(new \App\Mail\Notification($data));
          $m->processed = 1;
          $m->save();
          $this->info('mail sent to: ' . env('MAIL_TO'));
        }

        // Confirmation to user
        if ($m->type == 'confirmation') {
          \Mail::to($data->email)->send(new \App\Mail\Confirmation($data));
          $m->processed = 1;
          $m->save();
          $this->info('mail sent to: ' . $data->email);
        }
      } 
      catch(\Throwable $e) {
        $m->processed = 1;
        $m->error = $e;
        $m->save();
        \Log::error($e);
      }
    }  

    $this->info('queue completed...');

  }
}
