<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'customer_name' => 'sometimes|required|string|max:255',

            'order_date' => 'required|date',
            'description' => 'sometimes|required|string',
            'products' => 'sometimes|required|array',
            'products.*.id' => 'required_with:products|exists:products,id',
            'products.*.quantity' => 'required_with:products|integer|min:1',
        ];
    }

    /**
     * Get the custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'order_date.required' => 'La data dell\'ordine è obbligatorio.',
            'order_date.date' => 'La data dell\'ordine deve essere una data valida.',
            'customer_name.required' => 'Il nome del cliente è obbligatorio.',
            'customer_name.string' => 'Il nome del cliente deve essere una stringa.',
            'customer_name.max' => 'Il nome del cliente non può superare i 255 caratteri.',
            'description.required' => 'La descrizione è obbligatoria.',
            'description.string' => 'La descrizione deve essere una stringa.',
            'products.required' => 'È necessario fornire almeno un prodotto.',
            'products.array' => 'I prodotti devono essere un array.',
            'products.*.id.required' => 'L\'ID del prodotto è obbligatorio.',
            'products.*.id.exists' => 'Il prodotto selezionato non esiste.',
            'products.*.quantity.required' => 'La quantità del prodotto è obbligatoria.',
            'products.*.quantity.integer' => 'La quantità del prodotto deve essere un numero intero.',
            'products.*.quantity.min' => 'La quantità del prodotto deve essere almeno 1.',
        ];
    }
}
