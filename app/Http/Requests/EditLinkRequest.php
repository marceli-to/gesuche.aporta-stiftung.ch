<?php
namespace App\Http\Requests;

class EditLinkRequest extends FieldErrorRequest
{
  public function authorize()
  {
    return true;
  }

  public function rules()
  {
    return [
      'email' => 'required|email',
    ];
  }

  protected function fieldMessages(): array
  {
    return [
      'email.required' => [
        'field' => 'email',
        'error' => 'E-Mail wird benötigt',
      ],
      'email.email' => [
        'field' => 'email',
        'error' => 'E-Mail ist ungültig',
      ],
    ];
  }
}
