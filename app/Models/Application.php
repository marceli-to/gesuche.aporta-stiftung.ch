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
    'file_anunal_report',
    'file_annual_financial_report',
    'file_budget',
    'file_bylaws',
    'file_project_description',
    'file_project_estimated_costs',
  ];

  protected $casts = [
    'created_at' => 'date:d.m.Y',
    'updated_at' => 'date:d.m.Y',
  ];

  /**
   * The accessors to append to the model's array form.
   *
   * @var array
   */
  protected $appends = [
    'applicant_name',
    'applicant_email'
  ];

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
}
