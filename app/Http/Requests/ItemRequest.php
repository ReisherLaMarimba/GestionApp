<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Route;

class ItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Custom validation messages.
     */
    public function messages()
    {
        return [
            'min_quantity.lt' => 'The minimum quantity must be less than the maximum quantity.',
            'max_quantity.gt' => 'The maximum quantity must be greater than the minimum quantity.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // If you use route model binding, $this->route('item') will be the Item model or null
        $itemId = $this->route('item')?->id;

        $rules = [
            'item_code'     => [
                'required',
                'string',
                Rule::unique('items', 'item_code')->ignore($itemId), // This solves the Postgres error
            ],
            'name'          => 'required|string',
            'weight'        => 'numeric|nullable',
            'additionals'   => 'array|nullable',
            'additionals.*' => 'string',

            'min_quantity'  => [
                'required',
                'numeric',
                'lte:max_quantity',
            ],
            'max_quantity'  => [
                'required',
                'numeric',
                'gte:min_quantity',
            ],

            'description'   => 'required|string',
            'image'         => 'image|mimes:jpg,png,jpeg|max:2048|nullable',
            'comments'      => 'string|nullable',
            'category'      => 'required|integer|exists:categories,id',
            'location'      => 'required|integer|exists:locations,id',
        ];

        // Only require 'stock' if this is a create action
        if (Route::currentRouteName() === 'platform.items') {
            $rules['stock'] = 'required|numeric';
        }

        return $rules;
    }
}
