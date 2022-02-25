<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\DataCollection;
use App\Models\Application;
use App\Http\Requests\ApplicationStoreRequest;
use App\Services\Logger;
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
   * @param  \Illuminate\Http\ApplicationStoreRequest $request
   * @return \Illuminate\Http\Response
   */
  public function update(Application $application, ApplicationStoreRequest $request)
  {
    $application = Application::findOrFail($application->id);
    $application->update($request->all());
    $application->save();

    // Log change
    (new Logger())->log($application, 'saved');

    return response()->json('successfully updated');
  }

  /**
   * Archive a application for a given application
   *
   * @param Application $application
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function archive(Application $application, Request $request)
  {
    $application = Application::findOrFail($application->id);
    $application->update($request->all());
    $application->save();

    // Log change
    if ($request->input('archive'))
    {
      (new Logger())->log($application, 'archived');
    }

    return response()->json('successfully updated');
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
    (new Logger())->log($application, 'deleted');
    return response()->json('successfully deleted');
  }

}
