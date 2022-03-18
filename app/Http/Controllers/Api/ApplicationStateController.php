<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\DataCollection;
use App\Models\ApplicationState;
use Illuminate\Http\Request;

class ApplicationStateController extends Controller
{
  /**
   * Get a list of log entries
   * 
   * @return \Illuminate\Http\Response
   */
  public function get()
  {
    return new DataCollection(ApplicationState::byPermission()->orderBy('order')->get());
  }

}
