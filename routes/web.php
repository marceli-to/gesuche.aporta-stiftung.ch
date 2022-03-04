<?php
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\DownloadController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
*/
Route::get('/import', [PageController::class, 'import']);
Route::get('/downloads', [PageController::class, 'downloads']);


// Auth routes
Auth::routes(['verify' => true, 'register' => false]);
Route::get('/logout', 'Auth\LoginController@logout');
Route::get('/', [PageController::class, 'index'])->name('home');

Route::get('/pdf/{type}', [PageController::class, 'pdf'])->name('pdf');

// Logged in users
Route::middleware('auth:sanctum', 'verified')->group(function() {

  Route::get('/download/{folder}/{filename}', [DownloadController::class, 'download'])->name('download');

  Route::get('/gesuche/{any?}', function () {
    return view('layout.authenticated');
  })->where('any', '.*')->name('applications');
});


