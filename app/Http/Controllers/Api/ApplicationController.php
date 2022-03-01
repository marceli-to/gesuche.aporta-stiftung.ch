<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\DataCollection;
use App\Models\Application;
use App\Models\ApplicationUser;
use App\Http\Requests\ApplicationStoreRequest;
use App\Services\Logger;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
  /**
   * Get a list of active applications
   * 
   * @return \Illuminate\Http\Response
   */
  public function get()
  { 
    return new DataCollection(Application::current()->with('state', 'users')->orderBy('created_at', 'DESC')->get());
  }

  /**
   * Get a list of archived applications
   * 
   * @return \Illuminate\Http\Response
   */
  public function getArchive()
  { 
    return new DataCollection(Application::archive()->with('state', 'users')->orderBy('created_at', 'DESC')->get());
  }

  /**
   * Get a filtered ist of applications
   * 
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function filter(Request $request)
  { 
    if ($request->input('state') && $request->input('amount'))
    {
      $constraint = explode(':', $request->input('amount'));
      $operator = $constraint[0] == 'lt' ? '<=' : '>='; 
      $data = Application::current()->orderBy('created_at', 'DESC')->with('state', 'users')->where('application_state_id', $request->input('state'))->where('project_contribution_requested', $operator, $constraint[1])->get();
      return new DataCollection($data);
    }
    else
    {
      if ($request->input('state'))
      {
        $data = Application::current()->orderBy('created_at', 'DESC')->with('state', 'users')->where('application_state_id', $request->input('state'))->get();
        return new DataCollection($data);
      }

      if ($request->input('amount'))
      {
        $constraint = explode(':', $request->input('amount'));
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
    // Update 'has seen' state
    $applicationUser = ApplicationUser::where('application_id', $application->id)->where('user_id', auth()->user()->id)->get()->first();
    if (!$applicationUser)
    {
      ApplicationUser::create([
        'application_id' => $application->id,
        'user_id' => auth()->user()->id,
        'user_uuid' => auth()->user()->uuid,
      ]);
      (new Logger())->log($application, 'Gesucht geöffnet');
    }

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
