<?php
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
*/

Route::get('/formular', [FormController::class, 'index']);
Route::post('/form/submit', [FormController::class, 'store']);
Route::post('/form/validate-password', [FormController::class, 'validatePassword']);

// Edit link for applicants
Route::middleware('throttle:60,1')->group(function() {
  Route::get('/formular/bearbeiten/{token}', [FormController::class, 'edit'])->name('form.edit');
  Route::get('/form/edit/{token}', [FormController::class, 'show']);
  Route::get('/form/edit/{token}/file/{uuid}', [FormController::class, 'file']);
});
Route::put('/form/edit/{token}', [FormController::class, 'update'])->middleware('throttle:20,1');

Route::post('/file/upload', [FormController::class, 'upload']);
Route::delete('/file/upload/{filename}', [FormController::class, 'delete']);
// Route::get('/formular', [PageController::class, 'index']);

// Auth routes
Auth::routes(['verify' => true, 'register' => false]);
Route::get('/logout', [LoginController::class, 'logout']);
Route::get('/', [PageController::class, 'index'])->name('home');

// Logged in users
Route::middleware('auth:sanctum', 'verified')->group(function() {
  Route::get('/download/{folder}/{filename}', [DownloadController::class, 'download'])->name('download');
  Route::get('/export/{type?}/{archived?}/{year?}', [DownloadController::class, 'export'])->name('export');
  Route::get('/brief/{type}', [DownloadController::class, 'pdf'])->name('pdf');
  Route::get('/{any?}', function () {
    return view('layout.authenticated');
  })->where('any', '.*')->name('applications');
});


