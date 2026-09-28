<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->dog);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:100'],
            'breed' => ['sometimes', 'required', 'string', 'max:100'],
            'birthdate' => ['sometimes', 'required', 'date', 'before_or_equal:today'],
            'weight' => ['sometimes', 'required', 'integer', 'min:1', 'max:150'],
        ];
    }
}
