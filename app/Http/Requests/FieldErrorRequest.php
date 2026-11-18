<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;

/**
 * Form request whose 422 response lists each error as {field, error}.
 *
 * The Vue error handling (resources/js/mixins/ErrorHandling.js) maps
 * every validation message to errors[message.field] = message.error.
 * Subclasses define the messages in fieldMessages() instead of messages().
 */
abstract class FieldErrorRequest extends FormRequest
{
    /**
     * Messages keyed by "attribute.rule".
     *
     * @return array<string, array{field: string, error: string}>
     */
    abstract protected function fieldMessages(): array;

    public function messages(): array
    {
        return array_map(fn (array $message) => $message['error'], $this->fieldMessages());
    }

    protected function failedValidation(Validator $validator): void
    {
        $fields = [];
        foreach ($this->fieldMessages() as $key => $message) {
            $fields[Str::beforeLast($key, '.')] = $message['field'];
        }

        $errors = [];
        foreach ($validator->errors()->messages() as $attribute => $messages) {
            $errors[$attribute] = array_map(fn (string $error) => [
                'field' => $fields[$attribute] ?? $attribute,
                'error' => $error,
            ], $messages);
        }

        throw new HttpResponseException(response()->json([
            'message' => $validator->errors()->first(),
            'errors' => $errors,
        ], 422));
    }
}
