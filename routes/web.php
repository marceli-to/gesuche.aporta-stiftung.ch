<?php
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\FormController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
*/

Route::get('/formular/{key}', [FormController::class, 'index']);
Route::post('/form/submit', [FormController::class, 'store']);

Route::post('/file/upload', [FormController::class, 'upload']);
Route::delete('/file/upload/{filename}', [FormController::class, 'delete']);
Route::get('/formular', [PageController::class, 'index']);

// Auth routes
Auth::routes(['verify' => true, 'register' => false]);
Route::get('/logout', 'Auth\LoginController@logout');
Route::get('/', [PageController::class, 'index'])->name('home');

Route::get('/pdf/{type}', [PageController::class, 'pdf'])->name('pdf');

// Logged in users
Route::middleware('auth:sanctum', 'verified')->group(function() {
  Route::get('/download/{folder}/{filename}', [DownloadController::class, 'download'])->name('download');
  Route::get('/export/{type?}/{archived?}/{year?}', [DownloadController::class, 'export'])->name('export');
  Route::get('/brief/{type}', [DownloadController::class, 'pdf'])->name('pdf');
  Route::get('/{any?}', function () {
    return view('layout.authenticated');
  })->where('any', '.*')->name('applications');
});


