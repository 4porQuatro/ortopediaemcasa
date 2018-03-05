<?php

namespace App\Http\Controllers;

use App\Models\Pages\Page;


class HomeController extends Controller
{

    public function index()
    {
        $page = Page::find(1);

        $banner_categories = $this->getBannerCategories();
        $products = $this->getProducts();

        return view(
            'front.pages.home.index',
            compact(
                'page',
                'banner_categories',
                'products'
            )
        );
    }

    public function getBannerCategories()
    {
        $banner_category = new \stdClass;

        $banner_category->title = 'Calçado';
        $banner_category->subtitle = 'Meias Elásticas Juzo';
        $banner_category->image = '/front/images/thumbnails/thumbnail_1.jpg';
        $banner_category->link_path = '/';


        $banner_categories = [];

        for ($i = 0; $i < 3; $i++) {
            $banner_categories[] = $banner_category;
        }

        return $banner_categories;

    }

    public function getProducts()
    {
        $product = new \stdClass;

        $product->category = 'Ortopedia';
        $product->image = '';
        $product->title = 'Suporte de Ombro Orthia - NOVO';
        $product->price = '€66,17';
        $product->before_price = '€77,85';

        $products = [];

        for ($i = 0; $i < 8; $i++) {
            $products[] = $product;
        }

        return $products;
    }
}
