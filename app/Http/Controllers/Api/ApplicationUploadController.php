<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Models\Application;
use App\Http\Requests\ApplicationUploadRequest;
use App\Services\Media;
use App\Services\Logger;
use App\Http\Controllers\Controller;

class ApplicationUploadController extends Controller
{
  /**
   * Upload a file
   * 
   * @param  ApplicationUploadRequest $request
   * @return \Illuminate\Http\Response
   */

  public function store(ApplicationUploadRequest $request)
  { 
    $media = (new Media(['force_lowercase' => false, 'folder' => $request->input('uuid')]))->store($request);
    if ($media)
    { 
      if ($request->input('field'))
      {
        $application = Application::where('uuid', $request->input('uuid'))->get()->first();
        $application[$request->input('field')] = $media['name'];
        $application->save();
        (new Logger())->log($application, 'Neue Datei ' . $media['name'] . ' hochgeladen (Feld: ' . $request->input('field') .')');
      }
    }

    return response()->json($media);
  }

  /**
   * Delete a file
   * 
   * @param  String $filename
   * @return \Illuminate\Http\Response
   */

  public function destroy($filename)
  { 
    $media = (new Media())->remove($filename, TRUE);
    return response()->json($media);
  }
}
