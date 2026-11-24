<?php
namespace App\Services;
use App\Models\Application;
use App\Models\ApplicationLog;

class Logger
{ 

  /**
   * Add an entry. Without a logged-in user (applicant edits through the
   * edit link) user_id stays null.
   * 
   * @param Application $application
   * @param String $action
   * @param array|null $details
   */
  public function log(Application $application, $action, ?array $details = null)
  {
    return ApplicationLog::create(
      [
        'uuid' => \Str::uuid(),
        'action' => $action, 
        'details' => $details,
        'application_id' => $application->id, 
        'user_id' => auth()->id(),
      ]
    );
  }
}
