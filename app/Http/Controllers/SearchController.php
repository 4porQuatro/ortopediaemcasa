<?php
  namespace App\Http\Controllers;

  use Illuminate\Http\Request;

  class SearchController extends Controller{

    public function index(){
      $products = $this->getProducts();
      $partners = $this->getPartners();

      return view('front.pages.search.index', compact(
        'products',
        'partners'
      ));
    }

    public function getProducts(){

      $product = new \stdClass;

      $product->category = 'Ortopedia';
      $product->image = '';
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
