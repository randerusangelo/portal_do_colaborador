<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AptidaoFormRequest extends FormRequest
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
            'dia_nasc' => [
                'required'
            ],
            'matricula' => [
                'required'
            ]
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'dia_nasc.required'    => 'Informe o dia de nascimento',
            'matricula.required'   => 'Informe a matrícula'
        ];
    }
}
