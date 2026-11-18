<?php
namespace App\Http\Controllers;
use App\Http\Controllers\BaseController;

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
      return redirect('/gesuche/aktuell');
    }
    return view($this->viewPath . 'index');
  }
}
