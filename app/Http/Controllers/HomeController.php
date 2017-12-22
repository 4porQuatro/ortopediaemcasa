<?php
  namespace App\Http\Controllers;

  use Illuminate\Http\Request;

  class HomeController extends Controller{

    public function index(){

        $banner_categories = $this->getBannerCategories();
        $products = $this->getProducts();
        $partners = $this->getPartners();
        $features = $this->getFeatures();

        return view('front.pages.home.index', compact(
          'banner_categories',
          'products',
          'partners',
          'features'
        ));
    }

    public function getBannerCategories(){
      
      $banner_category = new \stdClass;
      
      $banner_category->title = 'Calçado';
      $banner_category->subtitle = 'Meias Elásticas Juzo';
      $banner_category->image = '/front/images/thumbnails/thumbnail_1.jpg';
      $banner_category->link_path = '/';


      $banner_categories = [];

      for($i = 0; $i < 3; $i++){
        $banner_categories[] = $banner_category;
      }

      return $banner_categories;
      
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

    public function getFeatures(){

      $feature = new \stdClass;

      $feature->icon = '/front/images/icons/Icones-01.svg';
      $feature->name = 'produtos a preços competitivos';

      $features = [];

      for($i = 0; $i < 3; $i++){
        $features[] = $feature;
      }

      return $features;
    }

  }
?>
