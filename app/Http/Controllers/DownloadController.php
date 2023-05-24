<?php
namespace App\Http\Controllers;
use App\Http\Controllers\BaseController;
use App\Services\Media;
use App\Exports\ApplicationExport;
use App\Exports\ApplicationExportByYear;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Application;
use App\Services\Pdf;
use Illuminate\Http\Request;

class DownloadController extends BaseController
{

  protected $headers = [
    'Content-Type: application/pdf',
    'Cache-Control: no-store, no-cache, must-revalidate',
    'Expires: Sun, 01 Jan 2014 00:00:00 GMT',
    'Pragma: no-cache'
  ];

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
   * Export current applications to excel
   * 
   * @return \Illuminate\Http\Response
   */

  public function export($type = NULL, $archived = FALSE, $year = NULL)
  {
    $filename = 'gesuche-' . date('d-m-Y-H:i:s') . '.xlsx';
    return Excel::download(new ApplicationExport($type, $archived, $year), $filename);
  }

  /**
   * Generate and download a pdf
   * 
   * @return \Illuminate\Http\Response
   */
  public function pdf($type = 'reply_approved')
  { 
    $applications = [];
    switch ($type) {
      case 'reply_approved';
        $applications = Application::approved()->get();
      break;
      case 'reply_denied';
        $applications = Application::denied()->get();
      break;
    }
    
    $pdf = (new Pdf())->createMany($applications, $type);
    return response()->download($pdf['path'], $pdf['name'], $this->headers);
  }
}
