<?php
namespace App\Http\Controllers;
use App\Http\Controllers\BaseController;
use App\Services\Media; 
use App\Http\Requests\FileUploadRequest;
use App\Http\Requests\ApplicationCreateRequest;
use App\Http\Requests\ApplicationUpdateByApplicantRequest;
use App\Services\ApplicationChanges;
use App\Models\Application;
use App\Models\ApplicationFile;
use App\Models\ApplicationState;
use App\Models\MailQueue;
use App\Services\Logger;
use Illuminate\Http\Request;

class FormController extends BaseController
{
  protected $viewPath = 'pages.';

  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Show the form
   *
   * @return \Illuminate\Http\Response
   */

  public function index($key = NULL)
  {
    return view($this->viewPath . 'form');
  }

  /**
   * Store a new application
   *
   * @param  \Illuminate\Http\ApplicationCreateRequest $request
   * @return \Illuminate\Http\Response
   */
  public function store(ApplicationCreateRequest $request)
  {
    $application = Application::create(array_merge($this->mapRequest($request), [
      'uuid' => \Str::uuid(),
      'application_state_id' => ApplicationState::OPEN,
      'year' => 2026,
    ]));

    // Set 'add_instit' total
    $application->project_add_instit_final_total = $this->addInstitTotal($application);
    $application->save();

    // Add files
    if ($request->input('files'))
    {
      foreach($request->input('files') as $key => $file)
      { 
        if (isset($file['name']))
        {
          $this->addFile($application, $file['name'], $file['title'] ?? '', $key);
        }
      }
    }

    MailQueue::create([
      'type' => 'notification',
      'data' => $application->toJson()
    ]);

    MailQueue::create([
      'type' => 'confirmation',
      'data' => $application->toJson()
    ]);

    return response()->json('successfully updated');
  }

  /**
   * Show the form for an applicant's edit link, or an error page if the
   * link doesn't work (anymore).
   *
   * @param  String $token
   * @return \Illuminate\Http\Response
   */
  public function edit($token)
  {
    if (!Application::byEditToken($token)->exists())
    {
      return response()->view($this->viewPath . 'form-link-invalid', [], 410);
    }
    return view($this->viewPath . 'form', ['editToken' => $token]);
  }

  /**
   * The application behind an edit link, with the applicant fields and the
   * files the applicant uploaded with the form (admin files are left out).
   *
   * @param  String $token
   * @return \Illuminate\Http\Response
   */
  public function show($token)
  {
    $application = $this->findByToken($token);

    $files = [];
    foreach ($application->files()->whereNotNull('key')->get() as $file)
    {
      $files[$file->key] = [
        'uuid' => $file->uuid,
        'name' => $file->name,
        'title' => $file->title,
        'existing' => true,
      ];
    }

    // Empty amounts of the further institutions are stored as 0.00. Return
    // them empty like a fresh form, otherwise required_with asks for a name.
    $fields = $application->only(Application::APPLICANT_FIELDS);
    foreach ([2, 3, 4, 5] as $i)
    {
      if ((float) $fields['project_add_instit_total_' . $i] == 0 && !$fields['project_add_instit_' . $i])
      {
        $fields['project_add_instit_total_' . $i] = '';
      }
    }

    return response()->json([
      'application' => $fields,
      'files' => (object) $files,
      'expires_at' => $application->edit_token_expires_at->toIso8601String(),
    ]);
  }

  /**
   * Download a file the applicant uploaded with the form.
   *
   * @param  String $token
   * @param  String $uuid
   * @return \Illuminate\Http\Response
   */
  public function file($token, $uuid)
  {
    $application = $this->findByToken($token);
    $file = $application->files()->whereNotNull('key')->where('uuid', $uuid)->firstOrFail();
    abort_unless(\Storage::exists('files/' . $application->uuid . '/' . $file->name), 404);
    return (new Media())->download($application->uuid, $file->name);
  }

  /**
   * Save the applicant's changes made through the edit link.
   *
   * @param  String $token
   * @param  ApplicationUpdateByApplicantRequest $request
   * @return \Illuminate\Http\Response
   */
  public function update($token, ApplicationUpdateByApplicantRequest $request)
  {
    $application = $this->findByToken($token);

    $application->fill($this->mapRequest($request));
    $application->project_add_instit_final_total = $this->addInstitTotal($application);
    $changes = ApplicationChanges::fields($application);

    // Files: keep, replace or remove each form file. Admin files (no key)
    // are never touched.
    $files = $request->input('files', []);
    foreach (array_keys(ApplicationFile::FORM_KEYS) as $key)
    {
      $payload = $files[$key] ?? null;
      $current = $application->files()->where('key', $key)->get();

      if (!empty($payload['existing']))
      {
        continue;
      }

      $new = isset($payload['name']) ? $payload['name'] : null;
      if ($current->isEmpty() && !$new)
      {
        continue;
      }

      foreach ($current as $file)
      {
        (new Media())->removeFromFolder($application->uuid, $file->name);
        $file->delete();
      }

      if ($new)
      {
        $this->addFile($application, $new, ApplicationFile::FORM_KEYS[$key], $key);
      }

      $changes[] = ApplicationChanges::file($key, $current->pluck('name')->implode(', ') ?: null, $new);
    }

    // The link works once: saving ends it. Further changes need a new link.
    $application->edited_by_applicant_at = now();
    $application->edit_token = null;
    $application->edit_token_expires_at = null;
    $application->save();

    (new Logger())->log(
      $application,
      $changes ? 'Gesuch durch Gesuchsteller/in bearbeitet' : 'Gesuch durch Gesuchsteller/in ohne Änderungen gespeichert',
      $changes ?: null
    );

    $data = json_encode([
      'application' => $application->fresh(),
      'changes' => $changes,
    ]);

    MailQueue::create([
      'type' => 'edit_notification',
      'data' => $data,
    ]);

    MailQueue::create([
      'type' => 'edit_confirmation',
      'data' => $data,
    ]);

    return response()->json([
      'changes' => count($changes),
    ]);
  }

  /**
   * Upload a file
   * 
   * @param  FileUploadRequest $request
   * @return \Illuminate\Http\Response
   */

  public function upload(FileUploadRequest $request)
  { 
    $media = (new Media(['force_lowercase' => false]))->store($request);
    return response()->json($media);
  }

  /**
   * Delete a file
   * 
   * @param  String $filename
   * @return \Illuminate\Http\Response
   */

  public function delete($filename)
  {
    $media = (new Media())->remove($filename, TRUE);
    return response()->json($media);
  }

  /**
   * Validate application password
   *
   * @param  Request $request
   * @return \Illuminate\Http\Response
   */
  public function validatePassword(Request $request)
  {
    // Password protection can be switched off via APORTA_FORM_PASSWORD in .env
    if (!config('client.form_password')) {
      return response()->json([
        'valid' => true,
        'message' => 'Passwortschutz ist deaktiviert.'
      ]);
    }

    $request->validate([
      'password' => 'required|string'
    ]);

    $passwordFile = storage_path('app/application-passwords.json');

    if (!file_exists($passwordFile)) {
      return response()->json([
        'valid' => false,
        'message' => 'Password-Datei nicht gefunden.'
      ], 500);
    }

    $data = json_decode(file_get_contents($passwordFile), true);

    if (!isset($data['passwords']) || !is_array($data['passwords'])) {
      return response()->json([
        'valid' => false,
        'message' => 'Ungültiges Password-Format.'
      ], 422);
    }

    $submittedPassword = $request->input('password');
    $passwordFound = false;
    $passwordValid = false;
    $now = now();

    foreach ($data['passwords'] as $index => $passwordEntry) {
      if ($passwordEntry['password'] === $submittedPassword) {
        $passwordFound = true;

        // Check if password has been used
        if ($passwordEntry['first_used_at'] === null) {
          // First time use - record timestamp
          $data['passwords'][$index]['first_used_at'] = $now->toIso8601String();
          file_put_contents($passwordFile, json_encode($data, JSON_PRETTY_PRINT));
          $passwordValid = true;
          break;
        } else {
          // Password was used before - check if within 24 hours
          $firstUsedAt = \Carbon\Carbon::parse($passwordEntry['first_used_at']);
          $hoursSinceFirstUse = $firstUsedAt->diffInHours($now);

          if ($hoursSinceFirstUse < 24) {
            // Still valid
            $passwordValid = true;
            break;
          } else {
            // Expired (more than 24 hours)
            $passwordValid = false;
            break;
          }
        }
      }
    }

    if (!$passwordFound) {
      return response()->json([
        'valid' => false,
        'message' => 'Dieses Passwort ist ungültig'
      ], 405);
    }

    if (!$passwordValid) {
      return response()->json([
        'valid' => false,
        'message' => 'Dieses Passwort ist abgelaufen oder wurde bereits verwendet'
      ], 405);
    }

    return response()->json([
      'valid' => true,
      'message' => 'Passwort gültig.'
    ]);
  }

  /**
   * Map the form input to application attributes. Used for new applications
   * and applicant edits, so both store the fields the same way.
   *
   * @param  Request $request
   * @return array
   */
  private function mapRequest(Request $request): array
  {
    return [
      'name' => $request->input('name'),
      'former_name' => $request->input('name_change') ? $request->input('former_name') : null,
      'legal_form' => $request->input('legal_form'),
      'website' => $request->input('website'),
      'bank_account' => $request->input('bank_account'),
      'iban' => $request->input('iban'),
      'beneficiary' => $request->input('beneficiary') ? $request->input('beneficiary') : null,
      'street' => $request->input('street'),
      'street_number' => $request->input('street_number') ? $request->input('street_number') : null,
      'zip' => $request->input('zip'),
      'city' => $request->input('city'),
      'firstname' => $request->input('firstname'),
      'lastname' => $request->input('lastname'),
      'phone' => $request->input('phone'),
      'email' => $request->input('email'),
      'portrait' => $request->input('portrait'),
      'project_title' => $request->input('project_title'),
      'project_description' => $request->input('project_description'),
      'project_finance' => $request->input('project_finance') ? $request->input('project_finance') : null,
      'justification_funds' => $request->input('justification_funds'),
      'project_beneficiaries' => $request->input('project_beneficiaries'),
      'proportion_residents_benefit_program' => $request->input('proportion_residents_benefit_program'),
      'number_residents_benefit_program' => $request->input('number_residents_benefit_program') ? $request->input('number_residents_benefit_program') : null,
      'project_time' => $request->input('project_time'),
      'project_cost_total' => $request->input('project_cost_total'),
      'project_own_contribution' => $request->input('project_own_contribution'),
      'project_contribution_requested' => $request->input('project_contribution_requested'),
      'project_contribution_further_requested' => $request->input('project_contribution_further_requested') ? $request->input('project_contribution_further_requested') : 0,
      'project_income' => $request->input('project_income'),
      'project_income_remarks' => $request->input('project_income_remarks') ? $request->input('project_income_remarks') : null,
      'project_add_instit_2' => $request->input('project_add_instit_2') ? $request->input('project_add_instit_2') : null,
      'project_add_instit_3' => $request->input('project_add_instit_3') ? $request->input('project_add_instit_3') : null,
      'project_add_instit_4' => $request->input('project_add_instit_4') ? $request->input('project_add_instit_4') : null,
      'project_add_instit_5' => $request->input('project_add_instit_5') ? $request->input('project_add_instit_5') : null,

      'project_add_instit_total_2' => $request->input('project_add_instit_total_2') ? $request->input('project_add_instit_total_2') : 0,
      'project_add_instit_total_3' => $request->input('project_add_instit_total_3') ? $request->input('project_add_instit_total_3') : 0,
      'project_add_instit_total_4' => $request->input('project_add_instit_total_4') ? $request->input('project_add_instit_total_4') : 0,
      'project_add_instit_total_5' => $request->input('project_add_instit_total_5') ? $request->input('project_add_instit_total_5') : 0,

      'project_add_instit_answer_2' => $request->input('project_add_instit_answer_2') ? $request->input('project_add_instit_answer_2') : null,
      'project_add_instit_answer_3' => $request->input('project_add_instit_answer_3') ? $request->input('project_add_instit_answer_3') : null,
      'project_add_instit_answer_4' => $request->input('project_add_instit_answer_4') ? $request->input('project_add_instit_answer_4') : null,
      'project_add_instit_answer_5' => $request->input('project_add_instit_answer_5') ? $request->input('project_add_instit_answer_5') : null,
    ];
  }

  private function addInstitTotal(Application $application)
  {
    return $application->project_add_instit_total_2 + $application->project_add_instit_total_3 + $application->project_add_instit_total_4 + $application->project_add_instit_total_5;
  }

  /**
   * Move a temp upload into the application folder and register it.
   */
  private function addFile(Application $application, string $name, string $title, $key = null): ApplicationFile
  {
    $file = ApplicationFile::create([
      'uuid' => \Str::uuid(),
      'name' => $name,
      'title' => $title,
      'key' => array_key_exists($key, ApplicationFile::FORM_KEYS) ? $key : null,
      'application_id' => $application->id,
      'user_id' => 1,
    ]);
    (new Media())->copyToApplicationFolder($name, $application->uuid);
    return $file;
  }

  /**
   * The application behind a working edit link, or 410.
   */
  private function findByToken($token): Application
  {
    $application = Application::byEditToken($token)->first();
    if (!$application)
    {
      abort(response()->json([
        'message' => 'Dieser Link ist nicht mehr gültig. Bitte wenden Sie sich an die Stiftung.',
      ], 410));
    }
    return $application;
  }
}
