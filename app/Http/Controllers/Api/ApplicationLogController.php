<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\DataCollection;
use App\Models\Application;
use App\Models\ApplicationLog;
use Illuminate\Http\Request;

class ApplicationLogController extends Controller
{
  /**
   * Get a list of log entries
   * 
   * @return \Illuminate\Http\Response
   */
  public function get(Application $application)
  {
    return new DataCollection(ApplicationLog::with('user', 'application')->where('application_id', $application->id)->orderBy('created_at', 'DESC')->get());
  }

}
