<?php
namespace App\Mail;
use App\Services\ApplicationChanges;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * To the foundation: the applicant changed their application.
 * $data is the decoded mail_queue data: {application, changes}.
 */
class EditNotification extends Mailable
{
  use Queueable, SerializesModels;

  public function __construct(protected $data) {}

  public function build()
  {
    $changes = json_decode(json_encode($this->data->changes), true);
    $bank = ApplicationChanges::hasBankChanges($changes);

    return $this
                ->subject(($bank ? 'ACHTUNG Bankangaben geändert: ' : 'Gesuch bearbeitet: ') . $this->data->application->name)
                ->with([
                  'application' => $this->data->application,
                  'changes' => $changes,
                  'bank' => $bank,
                ])
                ->markdown('mails.edit-notification');
  }
}
