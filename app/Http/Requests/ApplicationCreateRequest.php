<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class ApplicationCreateRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'name' => 'required',
      'legal_form' => 'required',
      'bank_account' => 'required',
      'iban' => 'required',
      'street' => 'required',
      'zip' => 'required',
      'city' => 'required',
      'firstname' => 'required',
      'lastname' => 'required',
      'phone' => 'required',
      'email' => 'required|email',
      'project_title' => 'required',
      'justification_funds' => 'required',
      'project_beneficiaries' => 'required',
      'proportion_residents_benefit_program' => 'required',
      'project_time' => 'required',
      'project_cost_total' => 'required',
      'project_own_contribution' => 'required',
      'project_income' => 'required',
      'project_contribution_requested' => 'required',
      'project_contribution_approved' => 'required',
      'project_contribution_further_requested' => 'required',
      'files.portrait' => 'required',
      'files.annual_report' => 'required',
      'files.annual_financial_report' => 'required',
      'files.budget' => 'required',
      'files.bylaws' => 'required',
      'files.project_description' => 'required',
      'files.project_estimated_costs' => 'required',
    ];
  }

  /**
   * Custom message for validation
   *
   * @return array
   */


  public function messages()
  {
    return [
      'name.required' => [
        'field' => 'name',
        'error' => 'Name der Institution wird benötigt'
      ],
      'legal_form.required' => [
        'field' => 'legal_form',
        'error' => 'Rechtsform wird benötigt'
      ],
      'bank_account.required' => [
        'field' => 'bank_account',
        'error' => 'Bankkonto wird benötigt'
      ],
      'iban.required' => [
        'field' => 'iban',
        'error' => 'IBAN wird benötigt'
      ],
      'street.required' => [
        'field' => 'street',
        'error' => 'Strasse wird benötigt'
      ],
      'zip.required' => [
        'field' => 'zip',
        'error' => 'PLZ wird benötigt'
      ],
      'city.required' => [
        'field' => 'city',
        'error' => 'Ort wird benötigt'
      ],
      'firstname.required' => [
        'field' => 'firstname',
        'error' => 'Vorname wird benötigt'
      ],
      'lastname.required' => [
        'field' => 'lastname',
        'error' => 'Name wird benötigt'
      ],
      'phone.required' => [
        'field' => 'phone',
        'error' => 'Telefon wird benötigt'
      ],
      'email.required' => [
        'field' => 'email',
        'error' => 'E-mail wird benötigt'
      ],
      'email.required' => [
        'field' => 'email',
        'error' => 'E-mail ist ungültig!'
      ],
      'project_title.required' => [
        'field' => 'project_title',
        'error' => 'Projekttitel wird benötigt'
      ],
      'justification_funds.required' => [
        'field' => 'justification_funds',
        'error' => 'Kurzbegründung Mittelbedarf wird benötigt'
      ],
      'project_beneficiaries.required' => [
        'field' => 'project_beneficiaries',
        'error' => 'Begünstigte des Projekts wird benötigt'
      ],
      'proportion_residents_benefit_program.required' => [
        'field' => 'proportion_residents_benefit_program',
        'error' => 'Anteil begünstigte Stadtzürcherinnen und Stadtzürcher wird benötigt'
      ],
      'project_time.required' => [
        'field' => 'project_time',
        'error' => 'Projektdauer/Zeitraum wird benötigt'
      ],
      'project_cost_total.required' => [
        'field' => 'project_cost_total',
        'error' => 'Total wird benötigt'
      ],
      'project_own_contribution.required' => [
        'field' => 'project_own_contribution',
        'error' => 'Eigenleistungen wird benötigt'
      ],
      'project_income.required' => [
        'field' => 'project_income',
        'error' => 'Budgetierter jährlicher Ertrag wird benötigt'
      ],
      'project_contribution_requested.required' => [
        'field' => 'project_contribution_requested',
        'error' => 'Beantragter Beitrag wird benötigt'
      ],
      'project_contribution_approved.required' => [
        'field' => 'project_contribution_approved',
        'error' => 'Genehmigter Betrag wird benötigt'
      ],
      'project_contribution_further_requested.required' => [
        'field' => 'project_contribution_further_requested',
        'error' => 'Weitere beantragte Beiträge (CHF) wird benötigt'
      ],
      'files.portrait.required' => [
        'field' => 'file_portrait',
        'error' => 'Kurzportrait (PDF) wird benötigt'
      ],
      'files.annual_report.required' => [
        'field' => 'file_annual_report',
        'error' => 'Jahresbericht (PDF) wird benötigt'
      ],
      'files.annual_financial_report.required' => [
        'field' => 'file_annual_financial_report',
        'error' => 'Jahresrechnung (PDF) wird benötigt'
      ],
      'files.budget.required' => [
        'field' => 'file_budget',
        'error' => 'Budget (PDF) wird benötigt'
      ],
      'files.bylaws.required' => [
        'field' => 'file_bylaws',
        'error' => 'Statuten (PDF) wird benötigt'
      ],
      'files.project_description.required' => [
        'field' => 'file_project_description',
        'error' => 'Beschreibung und Begründung des Projekts (PDF) wird benötigt'
      ],
      'files.project_estimated_costs.required' => [
        'field' => 'file_project_estimated_costs',
        'error' => 'Detaillierter Kostenvoranschlag (PDF) wird benötigt'
      ],
    ];
  }
}