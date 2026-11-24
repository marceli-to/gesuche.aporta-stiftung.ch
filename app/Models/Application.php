<?php
namespace App\Models;
use App\Models\ApplicationState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Base;
use Illuminate\Support\Str;

class Application extends Base
{
  use HasFactory, SoftDeletes;

  /**
   * Once an application leaves OPEN or is archived, its edit link is
   * deleted. Otherwise reversing the state back to OPEN (e.g. "Genehmigung
   * rückgängig machen") would silently revive a link the foundation closed.
   */
  protected static function booted(): void
  {
    static::saving(function (Application $application) {
      $leftOpen = $application->isDirty('application_state_id') && !$application->isNew();
      $archived = $application->isDirty('archive') && $application->archive;
      if (($leftOpen || $archived) && $application->edit_token !== null) {
        $application->edit_token = null;
        $application->edit_token_expires_at = null;
      }
    });
  }

  protected $fillable = [
    'uuid',
    'name',
    'former_name',
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
    'portrait',
    'project_title',
    'project_description',
    'justification_funds',
    'project_beneficiaries',
    'proportion_residents_benefit_program',
    'number_residents_benefit_program',
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
    'file_tax_exemption',
    'file_annual_report',
    'file_annual_financial_report',
    'file_budget',
    'file_bylaws',
    'file_project_description',
    'file_project_estimated_costs',
    'remarks',
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

  /**
   * Fields the applicant fills in on the public form and may change through
   * the edit link. Everything else (state, year, approvals, remarks, text
   * blocks) is managed by the foundation only.
   */
  public const APPLICANT_FIELDS = [
    'name',
    'former_name',
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
    'phone',
    'email',
    'portrait',
    'project_title',
    'project_description',
    'project_finance',
    'justification_funds',
    'project_beneficiaries',
    'proportion_residents_benefit_program',
    'number_residents_benefit_program',
    'project_time',
    'project_cost_total',
    'project_own_contribution',
    'project_contribution_requested',
    'project_contribution_further_requested',
    'project_income',
    'project_income_remarks',
    'project_add_instit_2',
    'project_add_instit_3',
    'project_add_instit_4',
    'project_add_instit_5',
    'project_add_instit_total_2',
    'project_add_instit_total_3',
    'project_add_instit_total_4',
    'project_add_instit_total_5',
    'project_add_instit_answer_2',
    'project_add_instit_answer_3',
    'project_add_instit_answer_4',
    'project_add_instit_answer_5',
  ];

  /**
   * Bank details. Changes by the applicant are highlighted to the foundation.
   */
  public const BANK_FIELDS = ['iban', 'bank_account', 'beneficiary'];

  protected $hidden = [
    'edit_token',
  ];

  protected $casts = [
    'created_at' => 'date:d.m.Y',
    'updated_at' => 'date:d.m.Y',
    'approved_at' => 'date:d.m.Y',
    'denied_at' => 'date:d.m.Y',
    'edit_token_expires_at' => 'datetime',
    'edited_by_applicant_at' => 'datetime',
  ];

  protected $appends = [
    'applicant_name',
    'applicant_email',
    'applicant_address',
    'requested_contribution',
    'created_at_timestamp',
    'created_at_formated',
    'created_at_long',
    'iban_formated',
    'is_new',
    'is_pending_approval',
    'is_in_process',
    'is_approved_external',
    'is_denied_external',
    'is_denied',
    'is_approved',
    'content_allocation',
    'edit_link_active',
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
   * Applications whose edit link matches the given token and still works:
   * not expired, state OPEN, not archived.
   */
  public function scopeByEditToken($query, ?string $token)
  {
    return $query
      ->where('edit_token', $token ? self::hashEditToken($token) : '')
      ->whereNotNull('edit_token')
      ->where('edit_token_expires_at', '>', now())
      ->where('application_state_id', ApplicationState::OPEN)
      ->where('archive', 0);
  }

  /**
   * Edit link helpers
   */

  /**
   * Create a new edit token (replacing any previous one) and return the
   * plain token. Only its hash is stored, so the link can't be read back
   * from the database later.
   */
  public function issueEditToken(int $hours): string
  {
    $token = Str::random(64);
    $this->edit_token = self::hashEditToken($token);
    $this->edit_token_expires_at = now()->addHours($hours);
    $this->save();
    return $token;
  }

  public function revokeEditToken(): void
  {
    $this->edit_token = null;
    $this->edit_token_expires_at = null;
    $this->save();
  }

  public function canBeEditedByApplicant(): bool
  {
    return $this->edit_token !== null
      && $this->edit_token_expires_at !== null
      && $this->edit_token_expires_at->isFuture()
      && $this->isNew()
      && !$this->archive;
  }

  public static function hashEditToken(string $token): string
  {
    return hash('sha256', $token);
  }

  public static function editUrl(string $token): string
  {
    return url('/formular/bearbeiten/' . $token);
  }

  public function getEditLinkActiveAttribute($value)
  {
    return $this->canBeEditedByApplicant();
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

  /**
   * Get 'created_at' as a written-out German date (e.g. "8. September 2025").
   * Used in the serial letters (Serienbrief) only.
   *
   * @param  string  $value
   * @return string
   */
  public function getCreatedAtLongAttribute($value)
  {
    $months = [
      1 => 'Januar', 2 => 'Februar', 3 => 'März', 4 => 'April',
      5 => 'Mai', 6 => 'Juni', 7 => 'Juli', 8 => 'August',
      9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Dezember',
    ];
    $ts = strtotime($this->created_at);
    return (int) date('j', $ts) . '. ' . $months[(int) date('n', $ts)] . ' ' . date('Y', $ts);
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

  /**
   * Get formated 'iban'
   *
   * @param  string  $value
   * @return string
   */
  public function getIbanFormatedAttribute($value)
  {
    if (!$this->iban) {
      return '';
    }
    // Stored IBAN has no "CH" prefix; prepend it and group in blocks of 4
    // (e.g. "CH93 0076 2011 6238 5295 7").
    return trim(chunk_split('CH' . $this->iban, 4, ' '));
  }
}
