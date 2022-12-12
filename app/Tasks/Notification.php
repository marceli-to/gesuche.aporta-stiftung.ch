<?php
namespace App\Tasks;

class Notification
{
  public function __invoke()
  {
    // Process mail queue
    $mails = \App\Models\MailQueue::unprocessed()->get();
    $mails = collect($mails)->splice(0,2);

    foreach($mails->all() as $m)
    {
      $data = json_decode($m->data);
      try {

        // Notification to owner
        if ($m->type == 'notification') {
          \Mail::to(env('MAIL_TO'))->send(new \App\Mail\Notification($data));
          $m->processed = 1;
          $m->save();
          \Log::info('mail sent to: ' . env('MAIL_TO'));
        }

        // Confirmation to user
        if ($m->type == 'confirmation') {
          \Mail::to($data->email)->send(new \App\Mail\Confirmation($data));
          $m->processed = 1;
          $m->save();
          \Log::info('mail sent to: ' . $data->email);
        }
      } 
      catch(\Throwable $e) {
        $m->processed = 1;
        $m->error = $e;
        $m->save();
        \Log::error($e);
      }
    }
  }
}