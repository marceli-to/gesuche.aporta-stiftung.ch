<?php
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
*/

// Auth routes
Auth::routes(['verify' => true, 'register' => false]);
Route::get('/logout', 'Auth\LoginController@logout');
Route::get('/', [PageController::class, 'index'])->name('home');

Route::get('/download/{filename}', [PageController::class, 'download'])->name('download');
Route::get('/pdf/{type}', [PageController::class, 'pdf'])->name('pdf');

// Logged in users
Route::middleware('auth:sanctum', 'verified')->group(function() {
  Route::get('/gesuche/{any?}', function () {
    return view('layout.authenticated');
  })->where('any', '.*')->middleware('role:admin')->name('applications');
});


