<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\UploadController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  return $request->user();
});

Route::middleware('auth:sanctum')->group(function() {
  Route::get('user', [UserController::class, 'find']);

  // Uploads
  Route::post('image/upload', [UploadController::class, 'image']);
  Route::post('file/upload', [UploadController::class, 'file']);

  // Applications
  Route::get('applications/{type?}', [ApplicationController::class, 'get']);
  Route::get('application/{application:uuid}', [ApplicationController::class, 'find']);
  Route::post('application', [ApplicationController::class, 'store']);
  Route::put('application/{application:uuid}', [ApplicationController::class, 'update']);
  Route::get('application/state/{application:uuid}', [ApplicationController::class, 'toggle']);
  Route::delete('application/{application:uuid}', [ApplicationController::class, 'destroy']);

});