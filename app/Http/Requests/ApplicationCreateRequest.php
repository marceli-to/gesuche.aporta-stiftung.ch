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
      'confirm_correctness' => 'accepted',
      'name' => 'required',
      'name_change' => 'required',
      'former_name' => 'required_if:name_change,yes',
      'legal_form' => 'required',
      // 'bank_account' => 'required',  // removed, unknown date, ms
      // 'iban' => 'required', // changed, 22.08.2025, ms
      'iban' => ['required', 'regex:/^CH\d{2}(?: ?\d{4}){4}\d$/'],
      'beneficiary' => 'required',
      'street' => 'required',
      'zip' => 'required',
      'city' => 'required',
      'firstname' => 'required',
      'lastname' => 'required',
      'phone' => 'required',
      'email' => 'required|email',
      'portrait' => 'required',
      'project_title' => 'required',
      'justification_funds' => 'required',
      'project_beneficiaries' => 'required',
      'project_description' => 'required',
      'proportion_residents_benefit_program' => 'required',
      'project_time' => 'required',
      'project_cost_total' => 'required',
      'project_own_contribution' => 'required',
      'project_income' => 'required',
      'project_income_remarks' => 'required',
      'project_contribution_requested' => 'required',
      //'project_contribution_further_requested' => 'required',  // removed, unknown date, ms
      // 'files.portrait' => 'required', // removed, 22.08.2025, ms
      'files.tax_exemption' => 'required',
      'files.annual_report' => 'required',
      'files.annual_financial_report' => 'required',
      'files.budget' => 'required',
      'files.bylaws' => 'required',
      // 'files.project_description' => 'required', // removed, 22.08.2025, ms
      'files.project_estimated_costs' => 'required',

      'project_add_instit_2' => 'nullable|required_with:project_add_instit_total_2',
      'project_add_instit_total_2' => 'nullable|required_with:project_add_instit_2',
      'project_add_instit_3' => 'nullable|required_with:project_add_instit_total_3',
      'project_add_instit_total_3' => 'nullable|required_with:project_add_instit_3',
      'project_add_instit_4' => 'nullable|required_with:project_add_instit_total_4',
      'project_add_instit_total_4' => 'nullable|required_with:project_add_instit_4',
      'project_add_instit_5' => 'nullable|required_with:project_add_instit_total_5',
      'project_add_instit_total_5' => 'nullable|required_with:project_add_instit_5',

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
      'confirm_correctness.accepted' => [
        'field' => 'confirm_correctness',
        'error' => 'Die oben gemachten Angaben sind vollständig und entsprechen der Wahrheit.'
      ],
      'name.required' => [
        'field' => 'name',
        'error' => 'Rechtlicher Name der Institution wird benötigt'
      ],
      'former_name.required_if' => [
        'field' => 'former_name',
        'error' => 'Vorheriger rechtlicher Name der Institution wird benötigt'
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
      'iban.regex' => [
        'field' => 'iban',
        'error' => 'IBAN ist ungültig'
      ],
      'beneficiary.required' => [
        'field' => 'beneficiary',
        'error' => 'Begünstigter wird benötigt'
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
      'email.email' => [
        'field' => 'email',
        'error' => 'E-mail ist ungültig!'
      ],
      'project_title.required' => [
        'field' => 'project_title',
        'error' => 'Projekttitel wird benötigt'
      ],
      'portrait.required' => [
        'field' => 'portrait',
        'error' => 'Kurzportrait wird benötigt'
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
        'error' => '% Anteil begünstigte Stadtzürcherinnen und Stadtzürcher wird benötigt'
      ],
      'project_description.required' => [
        'field' => 'project_description',
        'error' => 'Beschreibung und Begründung des Projekts wird benötigt'
      ],
      'project_time.required' => [
        'field' => 'project_time',
        'error' => 'Projektdauer/Zeitraum wird benötigt'
      ],
      'project_cost_total.required' => [
        'field' => 'project_cost_total',
        'error' => 'Projektkosten Total wird benötigt'
      ],
      'project_own_contribution.required' => [
        'field' => 'project_own_contribution',
        'error' => 'Eigenleistungen wird benötigt'
      ],
      'project_income.required' => [
        'field' => 'project_income',
        'error' => 'Budgetierter jährlicher Ertrag wird benötigt'
      ],
      'project_income_remarks.required' => [
        'field' => 'project_income_remarks',
        'error' => 'Kommentar zum jährlich budgetierten Ertrag wird benötigt'
      ],
      'project_contribution_requested.required' => [
        'field' => 'project_contribution_requested',
        'error' => 'Beantragter Beitrag wird benötigt'
      ],
      'project_contribution_approved.required' => [
        'field' => 'project_contribution_approved',
        'error' => 'Genehmigter Betrag wird benötigt'
      ],
      
      // 'project_contribution_further_requested.required' => [
      //   'field' => 'project_contribution_further_requested',
      //   'error' => 'Weitere beantragte Beiträge wird benötigt'
      // ], // removed, unknown date, ms

      // 'files.portrait.required' => [
      //   'field' => 'file_portrait',
      //   'error' => 'Kurzportrait wird benötigt'
      // ], // removed, 22.08.2025, ms

      'files.tax_exemption.required' => [
        'field' => 'file_tax_exemption',
        'error' => 'Aktuelle Steuerbefreiung (PDF) wird benötigt'
      ],
      'files.annual_report.required' => [
        'field' => 'file_annual_report',
        'error' => 'Jahresbericht wird benötigt'
      ],
      'files.annual_financial_report.required' => [
        'field' => 'file_annual_financial_report',
        'error' => 'Jahresrechnung wird benötigt'
      ],
      'files.budget.required' => [
        'field' => 'file_budget',
        'error' => 'Budget wird benötigt'
      ],
      'files.bylaws.required' => [
        'field' => 'file_bylaws',
        'error' => 'Statuten wird benötigt'
      ],
      // 'files.project_description.required' => [
      //   'field' => 'file_project_description',
      //   'error' => 'Beschreibung und Begründung des Projekts wird benötigt'
      // ], // removed, 22.08.2025, ms
      'files.project_estimated_costs.required' => [
        'field' => 'file_project_estimated_costs',
        'error' => 'Detaillierter Kostenvoranschlag wird benötigt'
      ],

      'project_add_instit_2.required_with' => [
        'field' => 'project_add_instit_2',
        'error' => 'Feld wird benötigt'
      ],
      'project_add_instit_total_2.required_with' => [
        'field' => 'project_add_instit_total_2',
        'error' => 'Feld wird benötigt'
      ],
      'project_add_instit_3.required_with' => [
        'field' => 'project_add_instit_3',
        'error' => 'Feld wird benötigt'
      ],
      'project_add_instit_total_3.required_with' => [
        'field' => 'project_add_instit_total_3',
        'error' => 'Feld wird benötigt'
      ],
      'project_add_instit_4.required_with' => [
        'field' => 'project_add_instit_4',
        'error' => 'Feld wird benötigt'
      ],
      'project_add_instit_total_4.required_with' => [
        'field' => 'project_add_instit_total_4',
        'error' => 'Feld wird benötigt'
      ],
      'project_add_instit_5.required_with' => [
        'field' => 'project_add_instit_5',
        'error' => 'Feld wird benötigt'
      ],
      'project_add_instit_total_5.required_with' => [
        'field' => 'project_add_instit_total_5',
        'error' => 'Feld wird benötigt'
      ],
    ];
  }
}