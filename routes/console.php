<?php

use App\Tasks\Notification;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| Scheduled tasks and closure based commands. Class based commands live
| in app/Console/Commands and are registered automatically.
|
*/

// Send queued confirmation and notification mails.
Schedule::call(new Notification)->everyMinute();
