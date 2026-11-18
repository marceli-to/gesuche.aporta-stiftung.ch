<?php
namespace App\Http\Requests;

class ApplicationFileUpdateRequest extends FieldErrorRequest
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
    ];
  }

  /**
   * Custom message for validation
   *
   * @return array
   */
  protected function fieldMessages(): array
  {
    return [
      'name.required' => [
        'field' => 'name',
        'error' => 'Datei wird benötigt!'
      ],
    ];
  }
}
