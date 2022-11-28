<?php
namespace App\Http\Controllers;
use App\Http\Controllers\BaseController;
use App\Services\Media; 
use App\Http\Requests\FileUploadRequest;
use App\Http\Requests\ApplicationCreateRequest;
use App\Models\Application;
use App\Models\ApplicationFile;
use App\Models\ApplicationState;
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

  public function index()
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
    $application = Application::create([
      'uuid' => \Str::uuid(),
      'name' => $request->input('name'),
      'legal_form' => $request->input('legal_form'),
      'website' => $request->input('website'),
      'bank_account' => $request->input('bank_account'),
      'iban' => $request->input('iban'),
      'beneficiary' => $request->input('beneficiary') ? $request->input('beneficiary') : null,
      'street' => $request->input('street'),
      'street_number' => $request->input('street_number'),
      'zip' => $request->input('zip'),
      'city' => $request->input('city'),
      'firstname' => $request->input('firstname'),
      'lastname' => $request->input('lastname'),
      'phone' => $request->input('phone'),
      'email' => $request->input('email'),
      'project_title' => $request->input('project_title'),
      'project_finance' => $request->input('project_finance') ? $request->input('project_finance') : null,
      'justification_funds' => $request->input('justification_funds'),
      'project_beneficiaries' => $request->input('project_beneficiaries'),
      'proportion_residents_benefit_program' => $request->input('proportion_residents_benefit_program'),
      'project_time' => $request->input('project_time'),
      'project_cost_total' => $request->input('project_cost_total'),
      'project_own_contribution' => $request->input('project_own_contribution'),
      'project_contribution_requested' => $request->input('project_contribution_requested'),
      'project_contribution_further_requested' => $request->input('project_contribution_further_requested'),
      'project_income' => $request->input('project_income'),
      'application_state_id' => ApplicationState::OPEN,
      'year' => 2023,
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
    ]);

    // Set 'add_instit' total
    $application->project_add_instit_final_total = $application->project_add_instit_total_2 + $application->project_add_instit_total_3 + $application->project_add_instit_total_4 + $application->project_add_instit_total_5;
    $application->save();

    // Add files
    if ($request->input('files'))
    {
      foreach($request->input('files') as $file)
      { 
        if (isset($file['name']))
        {
          $f = ApplicationFile::create([
            'uuid' => \Str::uuid(),
            'name' => $file['name'],
            'title' => $file['title'],
            'application_id' => $application->id,
            'user_id' => 1,
          ]);
          (new Media())->copyToApplicationFolder($file['name'], $application->uuid);
        }
      }
    }
    return response()->json('successfully updated');
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
}
