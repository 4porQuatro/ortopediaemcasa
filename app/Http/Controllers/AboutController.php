<?php
  namespace App\Http\Controllers;

  use Illuminate\Http\Request;

  class AboutController extends Controller{


    public function index(){

      $about_banner = $this->getAboutBanner();
      $about_description = $this->getAboutDescription();
      $partners = $this->getPartners();
      $features = $this->getFeatures();
      $section = $this->getPartnersSectionHead();

        return view('front.pages.about.index', compact(
          'about_banner',
          'about_description',
          'section',
          'partners',
          'features'

        ));
    }

    public function getAboutBanner(){
      $about_banner = new \stdClass;

      $about_banner->image = '/front/images/thumbnails/thumbnail_3.jpg';
      $about_banner->title = 'Ortopedia em casa';
      $about_banner->subtitle = '...chegou para si e por si';

      return $about_banner; 
    }

    public function getAboutDescription(){
      $about_description = new \stdClass;
      
      $about_description->text = '<p>A “Ortopedia em Casa®” surge com a missão de disponibilizar aos seus clientes as melhores soluções para cada caso em concreto, a um preço competitivo. Sendo um projeto novo, está alicerçado num grupo de empresas ligadas ao setor do comércio por grosso de artigos médicos, ortopédicos, de saúde e bem estar. Este facto permite-nos ter uma vasta experiência na seleção das melhores marcas e das melhores opções de mercado em cada artigo, privilegiando sempre e em primeiro lugar, a qualidade, e em segundo lugar, a competitividade em termos de preço.</p>';

      return $about_description;
    }

    public function getPartnersSectionHead(){
      $section = new \stdClass;

      $section->title = 'Porquê Ortopedia em Casa';
      $section->subtitle = 'artigos médicos, ortopédicos, de saúde e bem estar';

      return $section;
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
