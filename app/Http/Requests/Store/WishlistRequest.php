<?php

namespace App\Http\Requests\Store;

use App\Http\Requests\AppFormRequest;
use Illuminate\Validation\Rule;

class WishlistRequest extends AppFormRequest
{
    /**
     * The key to be used for the view error bag.
     *
     * @var string
     */
    protected $errorBag = 'wishlist';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'item_id' => 'required|exists:items,id'
        ];
    }
}
