<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveStoreRequest extends FormRequest
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
            'nombre' => 'required',
            'nombre_cliente' => 'required|string|max:255',
            'nit' => 'required',
            'api_coommerce' => 'required|url',
            'clave_key' => 'required',
            'clave_secret' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'nombre.required' => 'Ingrese el nombre de su tienda',
            'nit.required' => 'Ingrese el NIT de su tienda',
            'nit.unique' => 'El NIT ya está registrado en el sistema',

            'api_commerce.required' => 'La tienda necesita una API de su e-commerce',
            'api_commerce.url' => 'La URL de la API debe ser válida',
            'api_commerce.unique' => 'La API de e-commerce ya está registrada en el sistema',

            'clave_key.required' => 'La tienda necesita la clave Key',
            'clave_key.unique' => 'La clave Key ya está registrada en el sistema',

            'clave_secret.required' => 'La tienda necesita la clave Secret',
            'clave_secret.unique' => 'La clave Secret ya está registrada en el sistema',
        ];
    }
}
