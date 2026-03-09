<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnaliseCreditoFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cpf' => [
                'required'
            ],
            'matricula' => [
                'required'
            ],
            'quantidade' => [
                'required',
                'numeric',
                'between:1,12'
            ],
            'porcentagem' => [
                'required',
            ]
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'cpf'         => preg_replace("/[^0-9]/", "", $this->cpf ),
            'porcentagem' => str_replace( ',', '.', $this->porcentagem),
            'matricula'   => intval($this->matricula)
        ]);
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'cpf.required'         => 'Informe o CPF',
            'matricula.required'   => 'Informe a matrícula',
            'porcentagem.required' => 'Informe a porcentagem'
        ];
    }

    public function attributes()
    {
        return [
            'quantidade' => 'Qtd.Holerites'
        ];
    }
}
