<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\ApplicationCommentController;
use App\Http\Controllers\Api\ApplicationStateController;
use App\Http\Controllers\Api\ApplicationFileController;
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
  Route::post('application/file/upload', [ApplicationUploadController::class, 'store'])->middleware('role:admin');

  // Application states
  Route::get('application-states', [ApplicationStateController::class, 'get']);


  // Application comments
  Route::get('application-comments/{application:uuid}', [ApplicationCommentController::class, 'get']);
  Route::get('application-comment/{applicationComment:uuid}', [ApplicationCommentController::class, 'find']);
  Route::post('application-comment', [ApplicationCommentController::class, 'store']);
  Route::delete('application-comment/{applicationComment:uuid}', [ApplicationCommentController::class, 'destroy'])->middleware('role:admin');

  // Application files
  Route::post('application-file/store', [ApplicationFileController::class, 'store'])->middleware('role:admin');
  Route::delete('application-file/delete/{applicationFile:uuid}', [ApplicationFileController::class, 'destroy'])->middleware('role:admin');

  // Application logs
  Route::get('application-log/{application:uuid}', [ApplicationLogController::class, 'get']);

  // Applications
  Route::post('applications/filter', [ApplicationController::class, 'filter']);
  Route::get('applications/archiv', [ApplicationController::class, 'getArchive'])->middleware('role:admin');
  Route::get('applications/aktuell', [ApplicationController::class, 'get']);
  Route::put('application/approve/{application:uuid}', [ApplicationController::class, 'approve']);
  Route::get('application/{application:uuid}', [ApplicationController::class, 'find']);
  Route::get('application/reject/{application:uuid}', [ApplicationController::class, 'reject']);
  Route::delete('application/file/{application:uuid}/{field}', [ApplicationController::class, 'deleteFile'])->middleware('role:admin');
  Route::put('application/{application:uuid}', [ApplicationController::class, 'update']);
  Route::put('application/archive/{application:uuid}', [ApplicationController::class, 'archive'])->middleware('role:admin');
  Route::delete('application/{application:uuid}', [ApplicationController::class, 'destroy'])->middleware('role:admin');

});