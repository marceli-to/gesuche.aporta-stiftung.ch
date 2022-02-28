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
      return new DataCollection(Application::archive()->with('state')->orderBy('created_at', 'DESC')->get());
    }
    return new DataCollection(Application::current()->with('state')->orderBy('created_at', 'DESC')->get());

  }

  /**
   * Get a filtered ist of applications
   * 
   * @return \Illuminate\Http\Response
   */
  public function filter($stateId = NULL, $amount = NULL)
  { 
    if ($stateId != 'null' && $amount != 'null')
    {
      $constraint = explode(':', $amount);
      $operator = $constraint[0] == 'lt' ? '<=' : '>='; 
      $data = Application::current()->orderBy('created_at', 'DESC')->where('application_state_id', $stateId)->where('project_contribution_requested', $operator, $constraint[1])->get();
      return new DataCollection($data);
    }
    else
    {
      if ($stateId != 'null')
      {
        $data = Application::current()->orderBy('created_at', 'DESC')->where('application_state_id', $stateId)->get();
        return new DataCollection($data);
      }

      if ($amount != 'null')
      {
        $constraint = explode(':', $amount);
        $operator = $constraint[0] == 'lt' ? '<=' : '>='; 
        $data = Application::current()->orderBy('created_at', 'DESC')->where('project_contribution_requested', $operator, $constraint[1])->get();
        return new DataCollection($data);
      }

    } 

    return new DataCollection($data);
  }

  /**
   * Get a single applications for a given applications
   * 
   * @param Application $application
   * @return \Illuminate\Http\Response
   */
  public function find(Application $application)
  {
    return response()->json(Application::with('files.user')->findOrFail($application->id));
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

    (new Logger())->log($application, 'Gesuch gespeichert');
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
      (new Logger())->log($application, 'Gesuch archiviert');
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
    (new Logger())->log($application, 'Gesuch gelöscht');
    return response()->json('successfully deleted');
  }

  /**
   * Remove an application file
   *
   * @param  Application $application
   * @param String $field
   * @return \Illuminate\Http\Response
   */

  public function deleteFile(Application $application, $field = NULL)
  {
    (new Logger())->log($application, 'Datei ' . $application[$field] . ' (Feld: ' . $field. ') gelöscht');
    $application[$field] = null;
    $application->save();
    return response()->json('successfully deleted');
  }

}
