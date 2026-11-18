<?php
namespace App\Http\Requests;

class ApplicationCommentStoreRequest extends FieldErrorRequest
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
      'subject' => 'required',
      'comment' => 'required',
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
      'subject.required' => [
        'field' => 'subject',
        'error' => 'Betreff wird benötigt!'
      ],
      'comment.required' => [
        'field' => 'comment',
        'error' => 'Kommentar wird benötigt!'
      ],
    ];
  }
}
