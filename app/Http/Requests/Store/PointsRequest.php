<?php

namespace App\Http\Requests\Store;

use App\Http\Requests\AppFormRequest;

class PointsRequest extends AppFormRequest
{
    /**
     * The key to be used for the view error bag.
     *
     * @var string
     */
    protected $errorBag = 'points';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'points' => 'required|numeric|max:' . auth()->user()->getAvailablePoints() . '|min:1'
        ];
    }
}
