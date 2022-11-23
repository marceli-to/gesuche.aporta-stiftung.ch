<?php
namespace App\Http\Controllers;
use App\Http\Controllers\BaseController;
use App\Services\Media; 
use App\Http\Requests\FileUploadRequest;
use App\Http\Requests\ApplicationCreateRequest;
use Illuminate\Http\Request;

class FormController extends BaseController
{
  protected $viewPath = 'pages.';

  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Show the form
   *
   * @return \Illuminate\Http\Response
   */

  public function index()
  {
    return view($this->viewPath . 'form');
  }

  /**
   * Store a new application
   *
   * @param  \Illuminate\Http\ApplicationCreateRequest $request
   * @return \Illuminate\Http\Response
   */
  public function store(ApplicationCreateRequest $request)
  {
    // $application = Application::findOrFail($application->id);
    // (new Logger())->log($application, 'Gesuch erfasst');
    return response()->json('successfully updated');
  }

  /**
   * Upload a file
   * 
   * @param  FileUploadRequest $request
   * @return \Illuminate\Http\Response
   */

  public function upload(FileUploadRequest $request)
  { 
    $media = (new Media(['force_lowercase' => false]))->store($request);
    return response()->json($media);
  }

  /**
   * Delete a file
   * 
   * @param  String $filename
   * @return \Illuminate\Http\Response
   */

  public function delete($filename)
  { 
    $media = (new Media())->remove($filename, TRUE);
    return response()->json($media);
  }
}
