<?php

namespace App\Http\Controllers\PrivateArea;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Pages\Page;

class UserWelcomeController extends Controller
{
    /**
     * UserWelcomeController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Shows the Welcome.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $page = Page::find(11);

        return view(
            'front.pages.private-area.index',
            compact(
                'page'
            )
        );
    }
}
