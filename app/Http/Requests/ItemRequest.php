<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'min_quantity.lt' => 'La cantidad mínima debe ser menor que la cantidad máxima.',
            'max_quantity.gt' => 'La cantidad máxima debe ser mayor que la cantidad mínima.',
        ];
    }


    public function rules(): array
    {
        return [
            'item_code'    => 'required|string|unique:items,item_code',
            'name'         => 'required|string',
            'weight'       => 'numeric',
            'min_quantity' => [
                'required',
                'numeric',
                'lt:max_quantity', // Asegura que el valor sea menor que 'max_quantity'
            ],
            'max_quantity' => [
                'required',
                'numeric',
                'gt:min_quantity', // Asegura que el valor sea mayor que 'min_quantity'
            ],
            'stock'        => 'required|numeric',
            'description'  => 'required|string',
            'image'        => 'image|mimes:jpg,png,jpeg|max:2048',
            'comments'     => 'string',
            'category'     => 'required|integer|exists:categories,id',
            'location'     => 'required|integer|exists:locations,id',

        ];
    }
}
