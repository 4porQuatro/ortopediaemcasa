<?php

namespace App\Http\Controllers;

use App\Models\Items\Item;
use App\Models\Items\ItemBrand;
use App\Models\Items\ItemCategory;
use App\Models\Pages\Page;
use Illuminate\Http\Request;


class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $page = Page::find(2);

        $categories = ItemCategory::all();
        $brands = ItemBrand::all();
        $menu_html = ItemCategory::render(
            $categories,
            'ul',
            'li',
            function($menu_item)
            {
                $item_attrs = (!$menu_item->children->count()) ? ' class="products_filters_option" href="' . urli18n('products') . '?category=' . $menu_item->id . '"' : '';

                $active_class = (request()->get('cat') == $menu_item->id) ? ' class="active"' : '';

                return '<li' . $active_class . '><a ' . $item_attrs . ' data-filter="category" data-id="' . $menu_item->id . '">' . $menu_item->title . '</a>';
            }
        );


        $needle = $category_filter = $brand_filter = '%';
        if(request()->has('search'))
        {
            $needle = filter_var($request->get('search'), FILTER_SANITIZE_STRING);
        }
        if(request()->has('category'))
        {
            $category_filter = filter_var($request->get('category'), FILTER_SANITIZE_STRING);
        }
        if(request()->has('brand'))
        {
            $brand_filter = filter_var($request->get('brand'), FILTER_SANITIZE_STRING);
        }

        $products = Item::with('itemCategory')
            ->where('item_category_id', 'LIKE', $category_filter)
            ->where('item_brand_id', 'LIKE', $brand_filter)
            ->where(function($query) use($needle){
                $query->where('title', 'LIKE', '%' . $needle . '%')
                    ->orWhereHas('itemCategory', function($query) use($needle){
                        $query->where('title', 'LIKE', '%' . $needle . '%');
                    })
                    ->orWhereHas('itemBrand', function($query) use($needle){
                        $query->where('title', 'LIKE', '%' . $needle . '%');
                    });
            })
            ->paginate(18);

        return view(
            'front.pages.products.index',
            compact(
                'page',
                'menu_html',
                'brands',
                'products'
            )
        );
    }

    public function show(Request $request)
    {
        $product = Item::where('slug', $request->slug)
            ->with(
                [
                    'relatedItems',
                    'itemBrand',
                    'itemCategory.itemAttributeTypes.itemAttributeValues'
                ]
            )
            ->first();

        if(!$product)
        {
            abort(404);
        }

        return view(
            'front.pages.products.show',
            compact(
                'product'
            )
        );
    }
}
