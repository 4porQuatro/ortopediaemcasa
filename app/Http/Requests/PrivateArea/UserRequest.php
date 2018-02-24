<?php

namespace App\Http\Requests\PrivateArea;

use App\Http\Requests\AppFormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends AppFormRequest
{
    /**
     * The key to be used for the view error bag.
     *
     * @var string
     */
    protected $errorBag = 'user';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'billing_name' => 'required|max:100',
            'billing_phone' => 'required|max:20',
            'billing_address' => 'required|max:120',
            'billing_city' => 'required|max:60',
            'billing_zip_code' => 'required|max:20',
            'billing_country_id' => 'required|exists:geo_countries,id',
            'vat_number' => 'required|integer',
        ];

        switch($this->method())
        {
            default:
            {
                /* register rules */
                $rules['email'] = 'required|email|max:191|unique:users';
                $rules['password'] = 'required|min:6|max:191|confirmed';

                break;
            }
            case 'PATCH':
            {
                /* update rules */
                $rules['email'] = 'required|email|max:191|' . Rule::unique('users')->ignore($this->user->id);
                $rules['shipping_name'] = 'required|max:100';
                $rules['shipping_phone'] = 'required|max:20';
                $rules['shipping_address'] = 'required|max:120';
                $rules['shipping_city'] = 'required|max:60';
                $rules['shipping_zip_code'] = 'required|max:20';
                $rules['shipping_country_id'] = 'required|exists:geo_countries,id';

                break;
            }
        }

        return $rules;
    }
}
