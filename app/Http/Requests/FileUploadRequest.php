<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class FileUploadRequest extends FormRequest
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
      'file' => 'required|mimes:pdf,doc,docx,xls,xlsx,zip,png,jpg,jpeg,csv,txt|max:32000'
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
      'file.required' => [
        'field' => 'file',
        'error' => 'Es wird eine Datei benötigt!'
      ],
      'file.mimes' => [
        'field' => 'file',
        'error' => 'Dieser Dateityp ist nicht erlaubt'
      ],
      'file.max' => [
        'field' => 'file',
        'error' => 'Diese Datei ist zu gross (max. 32 MB)',
      ],
    ];
  }
}
