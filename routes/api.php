<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\ApplicationCommentController;
use App\Http\Controllers\Api\ApplicationLogController;
use App\Http\Controllers\Api\ApplicationUploadController;
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
  Route::post('application/file/upload', [ApplicationUploadController::class, 'store']);
  Route::post('file/upload', [UploadController::class, 'store']);

  // Application comments
  Route::get('application-comments/{application:uuid}', [ApplicationCommentController::class, 'get']);
  Route::get('application-comment/{applicationComment:uuid}', [ApplicationCommentController::class, 'find']);
  Route::post('application-comment', [ApplicationCommentController::class, 'store']);
  Route::delete('application-comment/{applicationComment:uuid}', [ApplicationCommentController::class, 'destroy']);

  // Application logs
  Route::get('application-log/{application:uuid}', [ApplicationLogController::class, 'get']);

  // Applications
  Route::get('applications/{type?}', [ApplicationController::class, 'get']);
  Route::get('application/{application:uuid}', [ApplicationController::class, 'find']);
  Route::delete('application/file/{application:uuid}/{field}', [ApplicationController::class, 'deleteFile']);
  Route::post('application', [ApplicationController::class, 'store']);
  Route::put('application/{application:uuid}', [ApplicationController::class, 'update']);
  Route::put('application/archive/{application:uuid}', [ApplicationController::class, 'archive']);
  Route::delete('application/{application:uuid}', [ApplicationController::class, 'destroy']);

});