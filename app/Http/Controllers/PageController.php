<?php
namespace App\Http\Controllers;
use App\Http\Controllers\BaseController;
use App\Services\Media;
use Illuminate\Http\Request;

class PageController extends BaseController
{
  protected $viewPath = 'pages.';

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
    if (auth()->user()) 
    {
      return redirect(route('applications'));
    }
    return redirect(route('login'));
  }

  /**
   * Download a file
   * 
   * @param String $filename
   * @return \Illuminate\Http\Response
   */

  public function download($filename)
  {
    return auth()->user() ? (new Media())->download($filename) : abort(403);
  }
}
