<?php

namespace App\Http\Requests\Newsletter;

use App\Http\Requests\AppFormRequest;

class SubscriptionRequest extends AppFormRequest
{
    /**
     * The key to be used for the view error bag.
     *
     * @var string
     */
    protected $errorBag = 'newsletter';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email|unique:newsletter_subscribers'
        ];
    }
}
