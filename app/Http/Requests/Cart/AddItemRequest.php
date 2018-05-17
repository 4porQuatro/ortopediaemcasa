<?php

namespace App\Http\Requests\Cart;

use App\Http\Requests\AppFormRequest;

use Gloudemans\Shoppingcart\Facades\Cart;

use App\Models\Items\Item;

class AddItemRequest extends AppFormRequest
{
    /**
     * The key to be used for the view error bag.
     *
     * @var string
     */
    protected $errorBag = 'add_item';

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
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1'
        ];
    }
}
