<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ErpRequest extends FormRequest
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
            'erp' => 'required|string|in:Allegra,Odoo,SAP,Microsoft Dynamics,Zoho ERP',
            'base_uri'=> '',
            'email' => ' required|email',
            'token' => 'required|string',
        ];
    }
}
