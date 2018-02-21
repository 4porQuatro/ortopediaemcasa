<?php

namespace App\Http\Controllers\PrivateArea;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Pages\Page;

class UserFavouritesController extends Controller
{
    /**
     * UserFavouritesController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Shows the favourites page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $page = Page::find(11);

        $user = \Auth::user();

        return view(
            'pages.user-favourites.index',
            compact(
                'page',
                'user'
            )
        );
    }
}
