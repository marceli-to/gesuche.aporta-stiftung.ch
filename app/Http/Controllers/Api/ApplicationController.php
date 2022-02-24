<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\DataCollection;
use App\Models\Application;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
  /**
   * Get a list of applications
   * 
   * @return \Illuminate\Http\Response
   */
  public function get($type = NULL)
  { 
    if ($type == 'archiv')
    {
      return new DataCollection(Application::archive()->orderBy('created_at', 'DESC')->get());
    }
    return new DataCollection(Application::current()->orderBy('created_at', 'DESC')->get());

  }

  /**
   * Get a single applications for a given applications
   * 
   * @param Application $application
   * @return \Illuminate\Http\Response
   */
  public function find(Application $application)
  {
    return response()->json(Application::findOrFail($application->id));
  }

  /**
   * Update a application for a given application
   *
   * @param Application $application
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function update(Application $application, Request $request)
  {
    $application = Application::findOrFail($application->id);
    $application->update($request->all());
    $application->save();
    return response()->json('successfully updated');
  }

  /**
   * Toggle the status a given application
   *
   * @param  Application $application
   * @return \Illuminate\Http\Response
   */
  public function toggle(Application $application)
  {
    $application->publish = $application->publish == 0 ? 1 : 0;
    $application->save();
    return response()->json($application->publish);
  }

  /**
   * Remove a application
   *
   * @param  Application $application
   * @return \Illuminate\Http\Response
   */
  public function destroy(Application $application)
  {
    $application->delete();
    return response()->json('successfully deleted');
  }

}
