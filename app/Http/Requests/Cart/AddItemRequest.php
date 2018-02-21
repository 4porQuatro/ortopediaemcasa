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
            'size_id' => 'required|exists:items_sizes,id',
            'color_id' => 'required|exists:items_colors,id',
            'quantity' => 'required|integer|min:1'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!$this->itemDataIsValid($validator)) {
                $validator->errors()->add('item_data', 'Os dados associados a este item não são válidos.');
            }
            if (!$this->stockIsValid($validator)) {
                $validator->errors()->add('item_stock', 'O produto não tem stock.');
            }
        });
    }

    private function itemDataIsValid($validator)
    {
        $item = Item::where('id', request('item_id'))
                        ->whereHas('sizes', function($query) use($validator){
                            $query->where('size_id', $validator->getData()['size_id'])
                                    ->where('stock', '>', 0);
                        })
                        ->whereHas('colors', function($query) use($validator){
                            $query->where('color_id', $validator->getData()['color_id'])
                                    ->where('stock', '>', 0);
                        })
                        ->first();

        return $item !== null;
    }

    private function stockIsValid($validator)
    {
        $has_stock = true;

        $cart = Cart::instance('items');

        $items = $cart->search(function ($cart_item, $row_id) use($validator) {
            return $cart_item->id == $validator->getData()['item_id']
                && $cart_item->options->color['id'] ==$validator->getData()['color_id']
                && $cart_item->options->size['id'] == $validator->getData()['size_id'];
        });

        if($items->count())
        {
            $item = $items->first();

            if($item->qty + $validator->getData()['quantity'] > $item->options->stock)
            {
                $has_stock = false;
            }
        }

        return $has_stock;
    }
}
