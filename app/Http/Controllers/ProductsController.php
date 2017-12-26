<?php
  namespace App\Http\Controllers;

  use Illuminate\Http\Request;

  class ProductsController extends Controller{

        public function index(){

            $menus = $this->getMenu();
            $products = $this->getProducts();
            $partners = $this->getPartners();

            return view('front.pages.products.index', compact(
                'menus',
                'products',
                'partners'
            ));
        }

        public function show(){
            return view('front.pages.products.show', []);
        }

        public function getMenu(){
            $menu = new \stdClass;

            $menu->first_level_item = 'Genero de Produto';
            $menu->second_level_item= 'Tipo de Produto';
            $menu->third_level_item = 'Produto';

            $menus = [];

            for($i = 0; $i < 3; $i++){
                $menus[] = $menu;
            }

            return $menus;
        }

        public function getProducts(){
            $product = new \stdClass;

            $product->category = 'Ortopedia';
            $product->title = 'Suporte de Ombro Orthia - NOVO';
            $product->price = '€66,17';
            $product->before_price = '€77,85';

            $products = [];

            for($i = 0; $i < 8; $i++){
              $products[] = $product;
            }
      
            return $products;
        }

        public function getPartners(){
            $partner = new \stdClass;

            $partner->image = '/front/images/logo/partners/AMD.jpg';
      
            $partners = [];
      
            for($i = 0; $i < 8; $i++){
              $partners[] = $partner;
            }
      
            return $partners;
        }

  }
?>
