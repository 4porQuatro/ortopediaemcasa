<?php
  namespace App\Http\Controllers;

  use App\Models\Pages\Page;
  use Illuminate\Http\Request;

  class AboutController extends Controller
  {
    public function index()
    {
        $page = Page::find(3);

        $partners = $this->getPartners();
        $features = $this->getFeatures();
        $section = $this->getPartnersSectionHead();

        return view(
            'front.pages.about.index',
            compact(
                'page',
                'section',
                'partners',
                'features'
            )
        );
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
