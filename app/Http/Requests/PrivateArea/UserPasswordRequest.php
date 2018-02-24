<?php

namespace App\Http\Requests\PrivateArea;

use App\Http\Requests\AppFormRequest;

class UserPasswordRequest extends AppFormRequest
{
    /**
     * The key to be used for the view error bag.
     *
     * @var string
     */
    protected $errorBag = 'user_pass';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'old_password' => 'required',
            'password' => 'required|min:6|max:191|confirmed'
        ];

        return $rules;
    }

    /**
     * Add an after hook to validate the user's old password.
     *
     * @param Validator $validator
     *
     * @return $validator
     */
    public function withValidator($validator)
    {
        if(!\Hash::check($this->old_password, auth()->user()->password))
        {
            $validator->after(function($validator)
            {
                $validator->errors()->add('old_password', trans('app.old-password-incorrect'));
            });
        }
    }
}
