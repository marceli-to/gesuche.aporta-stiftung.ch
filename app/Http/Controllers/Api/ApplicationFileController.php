<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\DataCollection;
use App\Models\Application;
use App\Models\ApplicationFile;
use App\Http\Requests\ApplicationFileStoreRequest;
use Illuminate\Http\Request;

class ApplicationFileController extends Controller
{
  /**
   * Store a newly created comment
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
    $applicationFile->delete();
    return response()->json('successfully deleted');
  }
}
