<?php
namespace App\Http\Controllers;
use App\Http\Controllers\BaseController;
use App\Services\Media;
use App\Exports\ApplicationExport;
use Maatwebsite\Excel\Facades\Excel;
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

  /**
   * Export Applications to Excel
   * 
   * @return \Illuminate\Http\Response
   */

  public function export()
  {
    $filename = 'gesuche-' . date('d-m-Y-H:i:s') . '.xlsx';
    return Excel::download(new ApplicationExport, $filename);
  }
}
