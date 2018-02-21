<?php

namespace App\Packages\IfThen\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IfThenPaymentReferenceRequest extends FormRequest
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
        /* register rules */
        return [
            'chave' => 'required|' . Rule::in([config('services.ifthen.antiphishing-key')]),
            'entidade' => 'required',
            'referencia' => 'required',
            'valor' => 'required'
        ];
    }
}
