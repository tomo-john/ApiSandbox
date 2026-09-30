<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateWalkRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->walk);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'walked_at' => ['sometimes', 'required', 'date', 'before_or_equal:now',],
            'duration_minutes' => ['sometimes', 'required', 'integer', 'min:1', 'max:720'],
            'distance_km' => ['sometimes', 'required', 'numeric', 'min:0.1', 'max:100'],
        ];
    }
}
