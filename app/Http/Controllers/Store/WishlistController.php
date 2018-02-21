<?php

namespace App\Http\Controllers\Store;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\Store\WishlistRequest;

use App\Models\Items\Item;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(WishlistRequest $request)
    {
        $item = Item::find($request->item_id);

        auth()->user()->items()->syncWithoutDetaching($item->id);

        $request->session()->flash('status', 'O item foi adicionado à sua Wishlist!');

        return back();
    }

    /**
     * Deletes a wishlist item.
     *
     * @param  array  $data
     * @return User
     */
    protected function destroy(Request $request, $id)
    {
        auth()->user()->items()->detach($id);

        $request->session()->flash('status', 'O item foi removido da sua Wishlist!');

        return back();
    }
}
