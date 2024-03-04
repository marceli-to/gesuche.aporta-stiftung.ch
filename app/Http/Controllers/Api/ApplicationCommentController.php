<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\DataCollection;
use App\Models\Application;
use App\Models\ApplicationComment;
use App\Http\Requests\ApplicationCommentStoreRequest;
use Illuminate\Http\Request;

class ApplicationCommentController extends Controller
{
  /**
   * Get a list of comments
   * 
   * @return \Illuminate\Http\Response
   */
  public function get(Application $application)
  {
    return new DataCollection(ApplicationComment::with('user', 'application')->where('application_id', $application->id)->orderBy('created_at', 'DESC')->get());
  }

  /**
   * Get a single comments for a given comments
   * 
   * @param ApplicationComment $comment
   * @return \Illuminate\Http\Response
   */
  public function find(ApplicationComment $comment)
  {
    return response()->json(ApplicationComment::with('user')->findOrFail($comment->id));
  }

  /**
   * Store a newly created comment
   *
   * @param  \Illuminate\Http\ApplicationCommentStoreRequest $request
   * @return \Illuminate\Http\Response
   */
  public function store(ApplicationCommentStoreRequest $request)
  {
    $application = Application::where('uuid', $request->input('uuid'))->get()->first();
    $comment = ApplicationComment::create([
      'uuid' => \Str::uuid(),
      'subject' => $request->input('subject'),
      'comment' => $request->input('comment'),
      'application_id' => $application->id,
      'user_id' => auth()->user()->id, 
    ]);
    return response()->json(['commentId' => $comment->id]);
  }

  /**
   * Remove a comment
   *
   * @param  ApplicationComment $comment
   * @return \Illuminate\Http\Response
   */
  public function destroy(ApplicationComment $applicationComment)
  {
    $applicationComment->delete();
    return response()->json('successfully deleted');
  }
}
