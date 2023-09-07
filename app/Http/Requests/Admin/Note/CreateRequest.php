<?php

namespace App\Http\Requests\Admin\Note;

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
            'location' => ['required', 'integer'],
            'clinician' => ['required', 'integer'],
            'error_type' => ['required', 'integer'],
            'patient' => ['required', 'string'],
            'date_of_service' => ['required', 'date'],
            'comment' => ['nullable', 'string', 'max:50000'],
        ];
    }
}
