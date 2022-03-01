<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\DataCollection;
use App\Models\Application;
use App\Models\ApplicationFile;
use App\Http\Requests\ApplicationFileStoreRequest;
use App\Services\Logger;
use Illuminate\Http\Request;

class ApplicationFileController extends Controller
{
  /**
   * Store a new file
   *
   * @param  \Illuminate\Http\ApplicationFileStoreRequest $request
   * @return \Illuminate\Http\Response
   */
  public function store(ApplicationFileStoreRequest $request)
  {
    $application = Application::where('uuid', $request->input('uuid'))->get()->first();
    $file = ApplicationFile::create([
      'uuid' => \Str::uuid(),
      'name' => $request->input('name'),
      'title' => $request->input('title'),
      'comment' => $request->input('comment'),
      'application_id' => $application->id,
      'user_id' => auth()->user()->id, 
    ]);

    (new Logger())->log($application, 'Neue Datei ' . $request->input('name') . ' hochgeladen.');


    return response()->json($file);
  }

  /**
   * Remove a file
   *
   * @param  ApplicationFile $applicationFile
   * @return \Illuminate\Http\Response
   */
  public function destroy(ApplicationFile $applicationFile)
  {
    $application = Application::findOrFail($applicationFile->application_id);
    (new Logger())->log($application, 'Datei ' . $applicationFile->name . ' gelöscht.');
    $applicationFile->delete();

    return response()->json('successfully deleted');
  }
}
