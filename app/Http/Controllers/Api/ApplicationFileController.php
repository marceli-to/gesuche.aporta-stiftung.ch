<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\DataCollection;
use App\Models\Application;
use App\Models\ApplicationFile;
use App\Http\Requests\ApplicationFileStoreRequest;
use App\Http\Requests\ApplicationFileUpdateRequest;
use App\Services\Logger;
use App\Services\Media;
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
   * Update a file
   * 
   * @param  ApplicationFile $applicationFile
   * @param  \Illuminate\Http\ApplicationFileUpdateRequest $request
   * @return \Illuminate\Http\Response
   */
  public function update(ApplicationFile $applicationFile, ApplicationFileUpdateRequest $request)
  {
    $applicationFile->name = $request->input('name');
    $applicationFile->save();

    $application = Application::find($applicationFile->application_id);
    (new Logger())->log($application, 'Datei ersetzt " '. $applicationFile->title .' " (' . $request->input('name') .')');
    return response()->json($applicationFile);
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
    (new Logger())->log($application, 'Datei ' . $applicationFile->name . ' ( ' . $applicationFile->title . ') gelöscht.');
    $applicationFile->delete();
    return response()->json('successfully deleted');
  }

  /**
   * Delete a file
   * 
   * @param String $uuid
   * @param  String $filename
   * @return \Illuminate\Http\Response
   */

  public function delete($uuid, $filename)
  { 
    $media = (new Media())->removeFromFolder($uuid, $filename);
    return response()->json($media);
  }
}
