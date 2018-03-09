<?php

namespace App\Http\Controllers;

use App\Models\Items\Item;
use App\Models\Items\ItemsCategory;
use App\Models\Pages\Page;


class HomeController extends Controller
{
    public function index()
    {
        $page = Page::find(1);

        $banner_categories = ItemsCategory::where('highlight', 1)->get();
        $products = Item::with('itemsCategory')->where('highlight', 1)->get();

        return view(
            'front.pages.home.index',
            compact(
                'page',
                'banner_categories',
                'products'
            )
        );
    }
}
