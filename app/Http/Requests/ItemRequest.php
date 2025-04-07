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
            'min_quantity.lt' => 'La cantidad mínima debe ser menor que la cantidad máxima.',
            'max_quantity.gt' => 'La cantidad máxima debe ser mayor que la cantidad mínima.',
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
                'lt:max_quantity', // Asegura que el valor sea menor que 'max_quantity'
            ],
            'max_quantity' => [
                'required',
                'numeric',
                'gt:min_quantity', // Asegura que el valor sea mayor que 'min_quantity'
            ],
            'description'  => 'required|string',
            'image'        => 'image|mimes:jpg,png,jpeg|max:2048',
            'comments'     => 'string',
            'category'     => 'required|integer|exists:categories,id',
            'location'     => 'required|integer|exists:locations,id',
        ];

        // Solo requerir 'stock' si la solicitud es de creación
        if (Route::currentRouteName() === 'platform.items') {
            $rules['stock'] = 'required|numeric';
        }

        return $rules;
    }

}
