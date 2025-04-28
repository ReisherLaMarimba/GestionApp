<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
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
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function messages()
    {
        return [
            'min_quantity.lt' => 'The minimum quantity must be less than the maximum quantity.',
            'max_quantity.gt' => 'The maximum quantity must be greater than the minimum quantity.',
        ];
    }


    public function rules(): array
    {
        $itemId = $this->route('item') ? $this->route('item')->id : null;

        $rules = [
            'item_code'    => 'required|string|unique:items,item_code,' . $itemId,
            'name'         => 'required|string',
            'weight'       => 'numeric',
            'additionals'  => 'array',
            'additionals.*' => 'string',
            'min_quantity' => [
                'required',
                'numeric',
                'lte:max_quantity',
            ],
            'max_quantity' => [
                'required',
                'numeric',
                'gte:min_quantity',
            ],
            'description'  => 'required|string',
            'image'        => 'image|mimes:jpg,png,jpeg|max:2048',
            'comments'     => 'string',
            'category'     => 'required|integer|exists:categories,id',
            'location'     => 'required|integer|exists:locations,id',
        ];

        // Only require 'stock' when creating (POST request)
        if ($this->isMethod('post')) {
            $rules['stock'] = 'required|numeric';
        }

        return $rules;
    }

}
