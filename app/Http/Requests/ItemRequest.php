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
        $itemId = $this->route('item')?->id;

        $rules = [
            'item_code'     => 'required|string|unique:items,item_code,' . ($itemId ?? 'NULL') . ',id',
            'name'          => 'required|string',
            'weight'        => 'nullable|numeric',
            'additionals'   => 'nullable|array',
            'additionals.*' => 'nullable|string',
            'min_quantity'  => [
                'required',
                'numeric',
                'lt:max_quantity', // Not LTE (less than ONLY)
            ],
            'max_quantity'  => [
                'required',
                'numeric',
                'gt:min_quantity', // Not GTE (greater than ONLY)
            ],
            'description'   => 'required|string',
            'image'         => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'comments'      => 'nullable|string',
            'category'      => 'required|integer|exists:categories,id',
            'location'      => 'required|integer|exists:locations,id',
        ];

        if ($this->isMethod('post')) {
            $rules['stock'] = 'required|numeric';
        }

        return $rules;
    }


}
