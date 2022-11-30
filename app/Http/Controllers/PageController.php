<?php
namespace App\Http\Controllers;
use App\Http\Controllers\BaseController;
use App\Services\Media;
use App\Services\Pdf;
use App\Models\Application;
use Illuminate\Http\Request;

class PageController extends BaseController
{
  protected $viewPath = 'pages.';

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
   * Show the homepage
   *
   * @return \Illuminate\Http\Response
   */

  public function index()
  {
    // if (auth()->user()) 
    // {
    //   return redirect('/gesuche/aktuell');
    // }
    // return redirect(route('login'));
    return view($this->viewPath . 'index');
  }

  /**
   * Download a file from storage
   * 
   * @param String $filename
   * @return \Illuminate\Http\Response
   */

  public function download($filename)
  {
    return auth()->user() ? (new Media())->download($filename) : abort(403);
  }

  /**
   * Generate and download a pdf
   * 
   * @return \Illuminate\Http\Response
   */
  public function pdf($type = NULL)
  {
    $pdf = (new Pdf())->createMany(Application::get(), $type);
    return response()->download($pdf['path'], $pdf['name'], $this->headers);
  }
}
