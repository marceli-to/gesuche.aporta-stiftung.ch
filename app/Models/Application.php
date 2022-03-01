<?php
namespace App\Models;
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
    'street',
    'street_number',
    'zip',
    'city',
    'firstname',
    'lastname',
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
    'project_contribution_approved',
    'project_income',
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
    'file_portrait',
    'file_annual_report',
    'file_annual_financial_report',
    'file_budget',
    'file_bylaws',
    'file_project_description',
    'file_project_estimated_costs',
    'archive',
    'application_state_id'
  ];

  protected $casts = [
    'created_at' => 'date:d.m.Y',
    'updated_at' => 'date:d.m.Y',
  ];

  protected $appends = [
    'applicant_name',
    'applicant_email',
    'applicant_address',
    'requested_contribution',
    'created_at_timestamp'
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
    return $this->hasMany(ApplicationComment::class, 'id', 'application_id');
  }

  public function logs()
  {
    return $this->hasMany(ApplicationLog::class, 'id', 'application_id');
  }


  /**
   * Local scopes 
   */

  public function scopeCurrent($query)
  {
    return $query->where('archive', 0);
  }

  public function scopeArchive($query)
  {
    return $query->where('archive', 1);
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
   * Get the applicants full name.
   *
   * @param  string  $value
   * @return string
   */
  public function getApplicantEmailAttribute($value)
  {
    return $this->email;
  }

  /**
   * Get the applicants full name.
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

}
