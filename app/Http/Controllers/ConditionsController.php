<?php

namespace App\Http\Controllers;

use App\Models\Pages\Page;
use Illuminate\Http\Request;


class ConditionsController extends Controller
{
    public function index()
    {
        $page = Page::find(7);

        return view(
            'front.pages.conditions.index',
            compact(
                'page'
            )
        );
    }

}
