<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\EditLinkRequest;
use App\Mail\EditLink;
use App\Models\Application;
use App\Services\Logger;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Edit links that let applicants correct their submitted application
 * (see PLAN-EDIT-LINK.md).
 */
class ApplicationEditLinkController extends Controller
{
  /**
   * Issue a new edit link (replacing any previous one) and mail it to the
   * applicant. The mail is sent right away and not through the mail queue,
   * because the queue stores its data in the database and the link must not
   * end up there. The response carries the URL, so the admin can copy it if
   * the mail fails.
   *
   * @param Application $application
   * @param EditLinkRequest $request
   * @return \Illuminate\Http\Response
   */
  public function store(Application $application, EditLinkRequest $request)
  {
    if (!$application->isNew() || $application->archive)
    {
      return response()->json([
        'message' => 'Ein Bearbeitungslink ist nur für offene Gesuche möglich.',
      ], 409);
    }

    $token = $application->issueEditToken(config('client.edit_link_days'));
    $url = Application::editUrl($token);
    $email = $request->input('email');

    $mailSent = true;
    try {
      Mail::to($email)->send(new EditLink($application, $url, $request->input('message')));
    }
    catch (\Throwable $e) {
      $mailSent = false;
      Log::error($e);
    }

    $expires = $application->edit_token_expires_at->format('d.m.Y H:i');
    (new Logger())->log($application, $mailSent
      ? "Bearbeitungslink gesendet an {$email} (gültig bis {$expires})"
      : "Bearbeitungslink erstellt (gültig bis {$expires}), Mail an {$email} fehlgeschlagen"
    );

    return response()->json([
      'url' => $url,
      'expires_at' => $application->edit_token_expires_at->toIso8601String(),
      'mail_sent' => $mailSent,
    ]);
  }

  /**
   * Revoke the edit link.
   *
   * @param Application $application
   * @return \Illuminate\Http\Response
   */
  public function destroy(Application $application)
  {
    $application->revokeEditToken();
    (new Logger())->log($application, 'Bearbeitungslink widerrufen');
    return response()->json('successfully revoked');
  }
}
