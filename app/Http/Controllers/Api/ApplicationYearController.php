<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\DataCollection;
use App\Models\Application;
use Illuminate\Http\Request;

class ApplicationYearController extends Controller
{
  /**
   * Get a list of application years
   * 
   * @return \Illuminate\Http\Response
   */
  public function get()
  {
    $applications = Application::get();

    // filter out duplicate years, remove ids
    $applicationYears = $applications->map(function ($application) {
      return $application->year;
    })->unique();

    // remove keys from array
    $applicationYears = array_values($applicationYears->toArray());

    // order by value descending
    rsort($applicationYears);
    return response()->json($applicationYears);
  }

}
