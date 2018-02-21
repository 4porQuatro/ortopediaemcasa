<?php

namespace App\Http\Controllers\PrivateArea;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Page;

class UserProfileController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    
    public function index()
    {
        $page = Page::find(17);

        $seo = new \stdClass();
        $seo->title = $page->title;
        $seo->description = $page->description;
        $seo->keywords = $page->keywords;

        $user = \Auth::user();

        return view(
            'pages.private.profile',
            compact(
                'page',
                'seo',
                'user'
            )
        );
    }
}
