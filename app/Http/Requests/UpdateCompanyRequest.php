<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $companyId = $this->route('company') ?? $this->route('id');

        return [
            'ruc' => [
                'required',
                'digits:11',
                Rule::unique('companies', 'ruc')->ignore($companyId),
            ],
            'razon_social' => ['required', 'string'],
            'departamento' => ['required', 'string'],
            'provincia' => ['required', 'string'],
            'distrito' => ['required', 'string'],
            'direccion' => ['required', 'string'],
        ];
    }
}
