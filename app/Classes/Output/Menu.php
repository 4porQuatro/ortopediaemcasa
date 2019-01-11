<?php

namespace App\Classes\Output;

use App\Models\Items\ItemCategory;



class Menu {

    public function getProductsMenuResponsive()
    {
        $categories = ItemCategory::all();
        $menu_html = ItemCategory::render(
            $categories,
            'ul',
            'li',
            function ($menu_item) {
                $item_attrs = ( ! $menu_item->children->count()) ? ' class="products_filters_option" href="' . urli18n('products') . '?category=' . $menu_item->id . '"' : '';

                $active_class = (request()->get('cat') == $menu_item->id) ? ' class="active"' : '';

                return '<li' . $active_class . '><a ' . $item_attrs . ' data-filter="category" data-id="' . $menu_item->id . '">' . $menu_item->title . '</a>';
            }
        );

        return $menu_html;
    }

}