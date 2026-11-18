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
      'message' => 'nullable|string|max:5000',
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
      'message.max' => [
        'field' => 'message',
        'error' => 'Die Nachricht ist zu lang',
      ],
    ];
  }
}
