<?php

namespace App\Http\Controllers;

use App\Models\Pages\Page;

class AboutController extends Controller
{
    public function index()
    {
        $page = Page::find(3);

        return view(
            'front.pages.about.index',
            compact(
                'page'
            )
        );
    }
}
