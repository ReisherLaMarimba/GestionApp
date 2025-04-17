<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignBasicItemRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'items_id' => 'required|array', // Ensure items_id is an array
            'items_id.*' => 'integer|exists:items,id', // Validate each item ID exists
            'user_id' => 'required|array|max:1', // Limit user IDs to a maximum of 2
            'user_id.*' => 'integer|exists:users,id', // Validate each user ID exists
        ];

    }

    public function messages(): array
    {
        return [
            'user_id.max' => 'Solo puede asignar hasta 1 usuarios por item.', // Error message
        ];
    }
}
