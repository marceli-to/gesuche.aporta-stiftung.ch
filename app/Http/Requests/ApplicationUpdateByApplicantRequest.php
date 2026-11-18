<?php
namespace App\Http\Requests;

use App\Models\Application;
use App\Models\ApplicationFile;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Validator;

/**
 * Same rules as a new application. Each file field is either a new temp
 * upload {name, title} or the file that is already there {uuid, existing: true},
 * so required files count as present when they are kept.
 */
class ApplicationUpdateByApplicantRequest extends ApplicationCreateRequest
{
  /**
   * Only with a working edit link. Checked before validation, so an expired
   * link gets 410 and not a list of field errors.
   */
  public function authorize()
  {
    return Application::byEditToken($this->route('token'))->exists();
  }

  protected function failedAuthorization(): void
  {
    throw new HttpResponseException(response()->json([
      'message' => 'Dieser Link ist nicht mehr gültig. Bitte wenden Sie sich an die Stiftung.',
    ], 410));
  }

  protected function fieldMessages(): array
  {
    // Map errors on files.<key> to the form field file_<key>, also for
    // optional files that have no "required" message.
    $messages = parent::fieldMessages();
    foreach (array_keys(ApplicationFile::FORM_KEYS) as $key) {
      $messages['files.' . $key . '.valid'] = [
        'field' => 'file_' . $key,
        'error' => 'Die Datei ist ungültig. Bitte laden Sie sie erneut hoch.',
      ];
    }
    return $messages;
  }

  public function after(): array
  {
    return [
      function (Validator $validator) {
        $application = Application::byEditToken($this->route('token'))->first();
        if (!$application) {
          return;
        }

        foreach (array_keys(ApplicationFile::FORM_KEYS) as $key) {
          $file = $this->input('files.' . $key);
          if (!$file) {
            continue;
          }

          $valid = is_array($file) && (!empty($file['existing'])
            ? $application->files()->where('key', $key)->where('uuid', $file['uuid'] ?? '')->exists()
            : self::isTempUpload($file['name'] ?? null));

          if (!$valid) {
            $validator->errors()->add('files.' . $key, 'Die Datei ist ungültig. Bitte laden Sie sie erneut hoch.');
          }
        }
      },
    ];
  }

  public static function isTempUpload($name): bool
  {
    return is_string($name)
      && $name !== ''
      && basename($name) === $name
      && Storage::exists('public/uploads/temp/' . $name);
  }
}
