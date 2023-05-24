<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\DataCollection;
use App\Models\Application;
use App\Models\ApplicationUser;
use App\Models\ApplicationState;
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
    if (auth()->user()->isAdmin()) {
      return new DataCollection(Application::current()->with('state', 'users')->orderBy('created_at', 'DESC')->get());
    }
    return new DataCollection(Application::current()->editor()->with('state', 'users')->orderBy('created_at', 'DESC')->get());

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
   * Search in applications
   * 
   * @param  \Illuminate\Http\Request $request
   */

  public function search($searchTerm = NULL, $type)
  {
    $query = Application::where('name', 'LIKE', "%{$searchTerm}%")
            ->orWhere('lastname', 'LIKE', "%{$searchTerm}%")
            ->orWhere('firstname', 'LIKE', "%{$searchTerm}%")
            ->orWhere('email', 'LIKE', "%{$searchTerm}%");

    if ($type == 'archiv')
    {
      $query = $query->archive();
    }
    else
    {
      $query = $query->current();
    }

    return new DataCollection($query->get());
  }

  /**
   * Get a filtered ist of applications
   * 
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function filter(Request $request, $type = NULL)
  { 
    $query = Application::orderBy('created_at', 'DESC')->with('state', 'users');

    if ($request->input('state'))
    {
      $query->where('application_state_id', $request->input('state'));
    }

    if ($request->input('amount'))
    {
      $constraint = explode(':', $request->input('amount'));
      $operator = $constraint[0] == 'lt' ? '<=' : '>='; 
      $query->where('project_contribution_requested', $operator, $constraint[1]);
    }

    if ($request->input('year'))
    {
      $query->where('year', $request->input('year'));
    }
    
    if ($type == 'archiv')
    {
      $query = $query->archive();
    }
    else
    {
      $query = $query->current();
    }

    return new DataCollection($query->get());
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
      (new Logger())->log($application, 'Gesuch geöffnet');
    }

    return response()->json(Application::with('files.user', 'comments')->findOrFail($application->id));
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
    $application->update($request->except(['approved_at', 'denied_at']));
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
   * Approve an application
   *
   * @param Application $application
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function approve(Application $application, Request $request)
  {
    $application = Application::findOrFail($application->id);

    $application_state_id = auth()->user()->isAdmin() ? ApplicationState::PENDING_APPROVAL : ApplicationState::APPROVED_EXTERNAL;

    $data = [
      'application_state_id' => $application_state_id,
      'approved_at' => \Carbon\Carbon::now(),
      'approved_by' => auth()->user()->id,
      'project_contribution_approved_temporary' => $request->input('project_contribution_approved_temporary') ? $request->input('project_contribution_approved_temporary') : 0
    ];

    $application->update($data);
    $application->save();

    $message = $application_state_id == ApplicationState::PENDING_APPROVAL ? 'Stiftung' : 'Stadt';
    (new Logger())->log($application, 'Gesuch durch '. $message .' geprüft');
    return response()->json('successfully updated');
  }

  /**
   * Definitively approve an application
   *
   * @param Application $application
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function approveFinal(Application $application, Request $request)
  {
    $application = Application::findOrFail($application->id);
    $application->project_contribution_approved = $request->input('project_contribution_approved') ? $request->input('project_contribution_approved') : $application->project_contribution_approved_temporary;
    $application->application_state_id = ApplicationState::APPROVED;
    $application->approved_at = \Carbon\Carbon::now();
    $application->approved_by = auth()->user()->id;
    $application->save();

    (new Logger())->log($application, 'Gesuch durch Stiftung definitiv genehmigt');
    return response()->json('successfully updated');
  }

  /**
   * Update an application
   *
   * @param Application $application
   * @return \Illuminate\Http\Response
   */
  public function save(Application $application, Request $request)
  {
    $application = Application::findOrFail($application->id);
    $application_state_id = ApplicationState::IN_PROCESS_EXTERNAL;
    $application->update([
      'application_state_id' => $application_state_id,
      'project_contribution_approved_temporary' => $request->input('project_contribution_approved_temporary')
    ]);
    $application->save();

    // Log message
    (new Logger())->log($application, 'Gesuch gespeichert');
    return response()->json('successfully updated');
  }

  /**
   * Deny an application
   *
   * @param Application $application
   * @return \Illuminate\Http\Response
   */
  public function deny(Application $application, Request $request)
  {
    $application = Application::findOrFail($application->id);

    $application_state_id = auth()->user()->isAdmin() ? ApplicationState::DENIED : ApplicationState::DENIED_EXTERNAL;

    $application->update([
      'application_state_id' => $application_state_id,
      'denied_at' => \Carbon\Carbon::now(),
      'denied_by' => auth()->user()->id,
    ]);
    $application->save();

    // Log message
    $message = $application_state_id == ApplicationState::DENIED ? 'Stiftung' : 'Stadt';

    (new Logger())->log($application, 'Gesuch durch '. $message .' abgelehnt');
    return response()->json('successfully updated');
  }

  /**
   * Reverse an application
   *
   * @param Application $application
   * @return \Illuminate\Http\Response
   */
  public function reverse(Application $application, Request $request)
  {
    $application = Application::findOrFail($application->id);
    $application->update([
      'project_contribution_approved_temporary' => 0,
      'application_state_id' => ApplicationState::OPEN,
      'approved_at' => NULL,
      'approved_by' => NULL,
      'denied_at' => NULL,
      'denied_by' => NULL,
    ]);
    $application->save();

    (new Logger())->log($application, 'Genehmigung wurde rückgängig gemacht');
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
}
