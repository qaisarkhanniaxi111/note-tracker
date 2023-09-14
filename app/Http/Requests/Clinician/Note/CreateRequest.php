<?php

namespace App\Http\Requests\Clinician\Note;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'note_status' => ['required', 'integer'],
            'status_reason' => ['nullable', 'string', 'max:30000']
        ];
    }
}
