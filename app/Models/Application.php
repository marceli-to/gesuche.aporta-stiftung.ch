<?php
namespace App\Models;
use App\Models\ApplicationState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Base;

class Application extends Base
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'uuid',
    'name',
    'legal_form',
    'website',
    'bank_account',
    'iban',
    'beneficiary',
    'street',
    'street_number',
    'zip',
    'city',
    'firstname',
    'lastname',
    'salutation',
    'phone',
    'email',
    'project_title',
    'justification_funds',
    'project_beneficiaries',
    'proportion_residents_benefit_program',
    'project_time',
    'project_cost_total',
    'project_own_contribution',
    'project_contribution_requested',
    'project_contribution_further_requested',
    'project_contribution_approved_temporary',
    'project_contribution_approved',
    'project_income',
    'project_income_remarks',
    'project_finance',
    'project_add_instit_2',
    'project_add_instit_total_2',
    'project_add_instit_answer_2',
    'project_add_instit_3',
    'project_add_instit_total_3',
    'project_add_instit_answer_3',
    'project_add_instit_4',
    'project_add_instit_total_4',
    'project_add_instit_answer_4',
    'project_add_instit_5',
    'project_add_instit_total_5',
    'project_add_instit_answer_5',
    'project_add_instit_final_total',
    'textblock_approval',
    'textblock_justification',
    'textblock_denial',
    'file_portrait',
    'file_annual_report',
    'file_annual_financial_report',
    'file_budget',
    'file_bylaws',
    'file_project_description',
    'file_project_estimated_costs',
    'remarks_percentage_of_residents_zurich',
    'remarks_direct_benefits_to_target_group',
    'remarks_exceptionality_of_project',
    'remarks_additional_relevant_information',
    'remarks_content_allocation',
    'year',
    'archive',
    'application_state_id',
    'approved_at',
    'approved_by',
    'denied_at',
    'denied_by'
  ];

  protected $casts = [
    'created_at' => 'date:d.m.Y',
    'updated_at' => 'date:d.m.Y',
    'approved_at' => 'date:d.m.Y',
    'denied_at' => 'date:d.m.Y',
  ];

  protected $appends = [
    'applicant_name',
    'applicant_email',
    'applicant_address',
    'requested_contribution',
    'created_at_timestamp',
    'created_at_formated',
    'is_new',
    'is_pending_approval',
    'is_in_process',
    'is_approved_external',
    'is_denied_external',
    'is_denied',
    'is_approved',
    'content_allocation',
  ];

  public function state()
  {
    return $this->hasOne(ApplicationState::class, 'id', 'application_state_id');
  }

  public function files()
  {
    return $this->hasMany(ApplicationFile::class, 'application_id', 'id');
  }

  public function comments()
  {
    return $this->hasMany(ApplicationComment::class, 'application_id', 'id');
  }

  public function logs()
  {
    return $this->hasMany(ApplicationLog::class, 'id', 'application_id');
  }

	public function users()
	{
		return $this->belongsToMany(User::class)->select('user_uuid');
	}

  /**
   * Local scopes 
   */

  public function scopeCurrent($query)
  {
    return $query->where('archive', 0);
  }

  public function scopeEditor($query)
  {
    return $query->where('application_state_id', '>', ApplicationState::OPEN);
  }

  public function scopeArchive($query)
  {
    return $query->where('archive', 1);
  }

  public function scopeApproved($query)
  {
    return $query->where('application_state_id', ApplicationState::APPROVED);
  }

  public function scopeDenied($query)
  {
    return $query->where('application_state_id', ApplicationState::DENIED);
  }

  /**
   * State helpers
   */

  public function isNew()
  {
    return $this->application_state_id == ApplicationState::OPEN ? TRUE : FALSE;
  }

  public function isPendingApproval()
  {
    return $this->application_state_id == ApplicationState::PENDING_APPROVAL ? TRUE : FALSE;
  }

  public function isInProcess()
  {
    return $this->application_state_id == ApplicationState::IN_PROCESS_EXTERNAL ? TRUE : FALSE;
  }

  public function isApprovedExternal()
  {
    return $this->application_state_id == ApplicationState::APPROVED_EXTERNAL ? TRUE : FALSE;
  }

  public function isDeniedExternal()
  {
    return $this->application_state_id == ApplicationState::DENIED_EXTERNAL ? TRUE : FALSE;
  }
  
  public function isDenied()
  {
    return $this->application_state_id == ApplicationState::DENIED ? TRUE : FALSE;
  }
  
  public function isApproved()
  {
    return $this->application_state_id == ApplicationState::APPROVED ? TRUE : FALSE;
  }

  /**
   * Get the applicants full name.
   *
   * @param  string  $value
   * @return string
   */

  public function getApplicantNameAttribute($value)
  {
    return $this->firstname . ' ' . $this->lastname;
  }

  /**
   * Get the applicants email
   *
   * @param  string  $value
   * @return string
   */
  public function getApplicantEmailAttribute($value)
  {
    return $this->email;
  }

  /**
   * Get the applicants address
   *
   * @param  string  $value
   * @return string
   */
  public function getApplicantAddressAttribute($value)
  {
    $address = '';
    if ($this->street)
    {
      $address .= $this->street . ' ';
    }
    if ($this->street_number)
    {
      $address .= $this->street_number;
    }

    if ($this->zip)
    {
      $address .= '<br>' . $this->zip . ' ';
    }

    if ($this->city)
    {
      $address .= $this->city;
    }

    return $address;
  }

  /**
   * For sorting purposes, get the requested contribution as integer 
   *
   * @param  string  $value
   * @return string
   */
  public function getRequestedContributionAttribute($value)
  {
    return (int) $this->project_contribution_requested;
  }

  /**
   * For sorting purposes, get the created at date timestamp 
   *
   * @param  string  $value
   * @return string
   */
  public function getCreatedAtTimestampAttribute($value)
  {
    return strtotime($this->created_at);
  }

  /**
   * Get formated 'created_at'
   *
   * @param  string  $value
   * @return string
   */
  public function getCreatedAtFormatedAttribute($value)
  {
    return date('d.m.Y', strtotime($this->created_at));
  }
  
  public function getIsNewAttribute($value)
  {
    return $this->isNew();
  }

  public function getIsInProcessAttribute($value)
  {
    return $this->isInProcess();
  }

  public function getIsPendingApprovalAttribute($value)
  {
    return $this->isPendingApproval();
  }

  public function getIsApprovedExternalAttribute($value)
  {
    return $this->isApprovedExternal();
  }

  public function getIsDeniedExternalAttribute($value)
  {
    return $this->isDeniedExternal();
  }

  public function getIsDeniedAttribute($value)
  {
    return $this->isDenied();
  }

  public function getIsApprovedAttribute($value)
  {
    return $this->isApproved();
  }

  public function getContentAllocationAttribute($value)
  {
    return explode(',', $this->remarks_content_allocation);
  }
}
