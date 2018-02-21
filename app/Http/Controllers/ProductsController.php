<?php
  namespace App\Http\Controllers;

  use App\Models\Pages\Page;
  use Illuminate\Http\Request;

  class ProductsController extends Controller{

        public function index()
        {
            $page = Page::find(2);

            $menus = $this->getMenu();
            $products = $this->getProducts();
            $partners = $this->getPartners();

            return view(
                'front.pages.products.index',
                compact(
                    'page',
                    'menus',
                    'products',
                    'partners'
                )
            );
        }

        public function show(){

            $products_slide = $this->getProductsSlide();
            $product_description = $this->getProductDescription();
            $product_price = $this->getProductPrice();
            $products_advise = $this->getProductsAdvise();

            return view('front.pages.products.show', compact(
                'products_slide',
                'product_description',
                'product_price',
                'products_advise'
            ));
        }

        public function getMenu(){
            $menu = new \stdClass;

            $menu->first_level_item = 'Genero de Produto';
            $menu->second_level_item = 'Tipo de Produto';
            $menu->third_level_item = ['Produto1', 'Produto2', 'Produto3'];

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
            $product->image = '/front/images/products/product_1.jpg';
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

        public function getProductsSlide(){
            $product_slide = new \stdClass;


            $product_slide->image = "/front/images/products/product_1.jpg";

            $products_slide = [];

            for($i = 0; $i < 3; $i++){
                $products_slide[] = $product_slide;
            }

            return $products_slide;

        }

        public function getProductDescription(){
            $product_description = new \stdClass;

            $product_description->category = 'Calçado';
            $product_description->title = 'Nome do Produto';
            $product_description->description = '<p>A nova palmilha Plantigel é fabricada com componentes de grau medicinal e essência de eucalipto natural pelas suas propriedades estimulantes e de efeito refrescante, 100% transpirável.</p>';

            return $product_description;
        }

        public function getProductPrice(){
            $product_price = new \stdClass;

            $product_price->before = '69,50€';
            $product_price->new = '77,40€';

            return $product_price;
        }

        public function getProductsAdvise(){

            $product_advise = new \stdClass;

            $product_advise->category = 'Ortopedia';
            $product_advise->title = 'Suporte de Ombro Orthia - NOVO';
            $product_advise->image = '/front/images/products/product_1.jpg';
            $product_advise->price = '€66,17';
            $product_advise->before_price = '€77,85';

            $products_advise = [];

            for($i = 0; $i < 6; $i++ ){
                $products_advise[] = $product_advise;
            }

            return $products_advise;
        }

  }
