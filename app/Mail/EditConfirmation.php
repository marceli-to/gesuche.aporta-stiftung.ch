<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * To the applicant: their changes were saved.
 * $data is the decoded mail_queue data: {application, changes}.
 */
class EditConfirmation extends Mailable
{
  use Queueable, SerializesModels;

  public function __construct(protected $data) {}

  public function build()
  {
    return $this
                ->subject('Änderungen an Ihrem Beitragsgesuch')
                ->with(['application' => $this->data->application])
                ->markdown('mails.edit-confirmation');
  }
}
