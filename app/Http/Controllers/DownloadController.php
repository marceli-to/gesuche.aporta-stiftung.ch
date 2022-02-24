<?php
namespace App\Http\Controllers;
use App\Http\Controllers\BaseController;
use App\Services\Media;
use Illuminate\Http\Request;

class DownloadController extends BaseController
{

  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Download a file from outside the public storage
   * 
   * @param String $filename
   * @return \Illuminate\Http\Response
   */

  public function download($folder = NULL, $filename = NULL)
  {
    if (!$filename) abort(404);
    return auth()->user() ? (new Media())->download($folder, $filename) : abort(403);
  }
}
