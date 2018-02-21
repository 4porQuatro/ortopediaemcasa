<?php

namespace App\Http\Controllers;

use App\Models\Pages\Page;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqsController extends Controller
{
    public function index()
    {
        $page = Page::find(4);

        $faqs = Faq::all();

        return view(
            'front.pages.faqs.index',
            compact(
                'page',
                'faqs'
            )
        );
    }
}
