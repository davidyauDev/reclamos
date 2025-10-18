<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'ruc' => ['required', 'digits:11', 'unique:companies,ruc'],
            'razon_social' => ['required', 'string'],
            'departamento' => ['required', 'string'],
            'provincia' => ['required', 'string'],
            'distrito' => ['required', 'string'],
            'direccion' => ['required', 'string'],
        ];
    }
}
