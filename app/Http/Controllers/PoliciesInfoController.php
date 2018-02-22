<?php

namespace App\Http\Controllers;

use App\Models\Pages\Page;
use Illuminate\Http\Request;


class PoliciesInfoController extends Controller
{
    public function index()
    {
        $page = Page::find(6);

        return view(
            'front.pages.policies.index',
            compact(
                'page'
            )
        );
    }
}
