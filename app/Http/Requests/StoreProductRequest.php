<?php
// App\Http\Requests\StoreProductRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:products,name',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0|max:1000000',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Il nome del prodotto è obbligatorio.',
            'name.unique' => 'Il nome del prodotto esiste già nel sistema.',
            'name.max' => 'Il nome del prodotto non può superare i 255 caratteri.',
            'description.required' => 'La descrizione del prodotto è obbligatoria.',
            'price.required' => 'Il prezzo del prodotto è obbligatorio.',
            'price.numeric' => 'Il prezzo deve essere un numero.',
            'price.min' => 'Il prezzo non può essere negativo.',
            'price.max' => 'Il prezzo non può essere maggiore di 1000000.',
        ];
    }
}
