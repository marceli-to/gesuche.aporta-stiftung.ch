<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class ApplicationStoreRequest extends FormRequest
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
      'street' => 'required',
      'street_number' => 'required',
      'zip' => 'required',
      'city' => 'required',
      'firstname' => 'required',
      'lastname' => 'required',
      'phone' => 'required',
      'email' => 'required|email',
      'legal_form' => 'required',
      'bank_account' => 'required',
      'project_title' => 'required',
      'project_time' => 'required',
      'project_cost_total' => 'required',
      'project_own_contribution' => 'required',
      'project_income' => 'required',
      'project_contribution_requested' => 'required',
      'project_contribution_approved' => 'required',
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
        'error' => 'Name wird benötigt!'
      ],
      'street.required' => [
        'field' => 'street',
        'error' => 'Strasse wird benötigt!'
      ],
      'street_number.required' => [
        'field' => 'street_number',
        'error' => 'Nummer wird benötigt!'
      ],
      'zip.required' => [
        'field' => 'zip',
        'error' => 'PLZ wird benötigt!'
      ],
      'city.required' => [
        'field' => 'city',
        'error' => 'Ort wird benötigt!'
      ],
      'firstname.required' => [
        'field' => 'firstname',
        'error' => 'Vorname wird benötigt!'
      ],
      'lastname.required' => [
        'field' => 'lastname',
        'error' => 'Name wird benötigt!'
      ],
      'phone.required' => [
        'field' => 'phone',
        'error' => 'Telefon wird benötigt!'
      ],
      'email.required' => [
        'field' => 'email',
        'error' => 'E-mail wird benötigt!'
      ],
      'email.required' => [
        'field' => 'email',
        'error' => 'E-mail ungültig!'
      ],
      'legal_form.required' => [
        'field' => 'legal_form',
        'error' => 'Rechtsform wird benötigt!'
      ],
      'bank_account.required' => [
        'field' => 'bank_account',
        'error' => 'Bankkonto wird benötigt!'
      ],
      'project_title.required' => [
        'field' => 'project_title',
        'error' => 'Projekttitel wird benötigt!'
      ],
      'project_time.required' => [
        'field' => 'project_time',
        'error' => 'Projektdauer wird benötigt!'
      ],
      'project_cost_total.required' => [
        'field' => 'project_cost_total',
        'error' => 'Projektkosten Total wird benötigt!'
      ],
      'project_own_contribution.required' => [
        'field' => 'project_own_contribution',
        'error' => 'Eigenleistungen wird benötigt!'
      ],
      'project_income.required' => [
        'field' => 'project_income',
        'error' => 'Budgetierter jährlicher Ertrag wird benötigt!'
      ],
      'project_contribution_requested.required' => [
        'field' => 'project_contribution_requested',
        'error' => 'Beantragter Beitrag wird benötigt!'
      ],
      'project_contribution_approved.required' => [
        'field' => 'project_contribution_approved',
        'error' => 'Genehmigter Betrag wird benötigt!'
      ],
    ];
  }
}