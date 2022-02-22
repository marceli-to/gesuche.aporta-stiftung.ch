<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\UploadController;
use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\BookingParticipantController;
use App\Http\Controllers\Api\ParticipantController;
use App\Http\Controllers\Api\ParticipantSubscriptionController;
use App\Http\Controllers\Api\NewsletterController;
use App\Http\Controllers\Api\NewsletterArticleController;
use App\Http\Controllers\Api\NewsletterArticleImageController;
use App\Http\Controllers\Api\SubscriberController;

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

  // Articles
  Route::get('articles', [ArticleController::class, 'get']);
  Route::get('article/{article}', [ArticleController::class, 'find']);
  Route::post('article', [ArticleController::class, 'store']);
  Route::put('article/{article}', [ArticleController::class, 'update']);
  Route::post('articles/order', [ArticleController::class, 'order']);
  Route::get('article/state/{article}', [ArticleController::class, 'toggle']);
  Route::delete('article/{article}', [ArticleController::class, 'destroy']);

  // Files
  Route::get('files/list', [FileController::class, 'list']);
  Route::get('files', [FileController::class, 'get']);
  Route::get('file/{file}', [FileController::class, 'find']);
  Route::post('file', [FileController::class, 'store']);
  Route::put('file/{file}', [FileController::class, 'update']);
  Route::get('file/state/{file}', [FileController::class, 'toggle']);
  Route::delete('file/byName/{file}', [FileController::class, 'destroyFile']);
  Route::delete('file/{file}', [FileController::class, 'destroy']);

  // Brand
  Route::get('brands', [BrandController::class, 'get']);
  Route::get('brand/{brand}', [BrandController::class, 'find']);
  Route::post('brand', [BrandController::class, 'store']);
  Route::put('brand/coords/{brand}', [BrandController::class, 'coords']);
  Route::put('brand/{brand}', [BrandController::class, 'update']);
  Route::get('brand/state/{brand}', [BrandController::class, 'toggle']);
  Route::delete('brand/{brand}', [BrandController::class, 'destroy']);

  // Image
  Route::get('images', [ImageController::class, 'get']);
  Route::post('images/order', [ImageController::class, 'order']);
  Route::get('image/{image}', [ImageController::class, 'find']);
  Route::post('image', [ImageController::class, 'store']);
  Route::put('image/coords/{image}', [ImageController::class, 'coords']);
  Route::put('image/{image}', [ImageController::class, 'update']);
  Route::get('image/state/{image}', [ImageController::class, 'toggle']);
  Route::delete('image/{image}', [ImageController::class, 'destroy']);

  // Events
  Route::get('events', [EventController::class, 'get']);
  Route::get('event/{event}', [EventController::class, 'find']);
  Route::post('event', [EventController::class, 'store']);
  Route::put('event/{event}', [EventController::class, 'update']);
  Route::get('event/state/{event}', [EventController::class, 'toggle']);
  Route::delete('event/{event}', [EventController::class, 'destroy']);

  // Bookings
  Route::get('bookings/{event}', [BookingController::class, 'get']);
  Route::get('booking/{booking}', [BookingController::class, 'find']);
  Route::get('booking/update/billing/{booking}/', [BookingController::class, 'update']);
  Route::delete('booking/{booking}', [BookingController::class, 'destroy']);

  // Booking participants
  Route::get('booking/participants/{booking}', [BookingParticipantController::class, 'find']);
  Route::delete('booking/participant/{bookingParticipant}', [BookingParticipantController::class, 'destroy']);

  // Participants
  Route::get('participants', [ParticipantController::class, 'get']);
  Route::get('participant/{participant}', [ParticipantController::class, 'find']);
  Route::post('participant', [ParticipantController::class, 'store']);
  Route::put('participant/{participant}', [ParticipantController::class, 'update']);
  Route::delete('participant/{participant}', [ParticipantController::class, 'destroy']);

  // Participant subscription
  Route::post('participant/subscription', [ParticipantSubscriptionController::class, 'store']);
  Route::delete('participant/subscription/{participantSubscription}', [ParticipantSubscriptionController::class, 'destroy']);

  // Newsletter articles
  Route::get('newsletter/articles/{newsletter}', [NewsletterArticleController::class, 'get']);
  Route::get('newsletter/article/{newsletterArticle}', [NewsletterArticleController::class, 'find']);
  Route::post('newsletter/article', [NewsletterArticleController::class, 'store']);
  Route::put('newsletter/article/{newsletterArticle}', [NewsletterArticleController::class, 'update']);
  Route::post('newsletter/articles/order', [NewsletterArticleController::class, 'order']);
  Route::delete('newsletter/article/{newsletterArticle}', [NewsletterArticleController::class, 'destroy']);

  // Newsletter article images
  Route::get('newsletter/article/image/state/{newsletterArticleImage}', [NewsletterArticleImageController::class, 'toggle']);
  Route::put('newsletter/article/image/coords/{newsletterArticleImage}', [NewsletterArticleImageController::class, 'coords']);
  Route::post('newsletter/article/image', [NewsletterArticleImageController::class, 'store']);
  Route::post('newsletter/article/image/order', [NewsletterArticleImageController::class, 'order']);
  Route::delete('newsletter/article/image/{newsletterArticleImage}', [NewsletterArticleImageController::class, 'destroy']);

  // Newsletter
  Route::get('newsletters', [NewsletterController::class, 'get']);
  Route::get('newsletter/{newsletter}', [NewsletterController::class, 'find']);
  Route::post('newsletter', [NewsletterController::class, 'store']);
  Route::put('newsletter/{newsletter}', [NewsletterController::class, 'update']);
  Route::get('newsletter/state/{newsletter}', [NewsletterController::class, 'toggle']);
  Route::delete('newsletter/queue/clear/{newsletter}', [NewsletterController::class, 'clearQueue']);
  Route::delete('newsletter/{newsletter}', [NewsletterController::class, 'destroy']);
  Route::get('newsletter/testmail/{newsletter}/{email}', [NewsletterController::class, 'sendTest']);
  Route::get('newsletter/queue/{newsletter}', [NewsletterController::class, 'send']);


  // Newsletter subscribers
  Route::get('subscribers', [SubscriberController::class, 'get']);
  Route::get('subscriber/{subscriber}', [SubscriberController::class, 'find']);
  Route::post('subscriber', [SubscriberController::class, 'store']);
  Route::put('subscriber/{subscriber}', [SubscriberController::class, 'update']);
  Route::delete('subscriber/{subscriber}', [SubscriberController::class, 'destroy']);
});



