<?php
  namespace App\Http\Controllers;

  use Illuminate\Http\Request;

  class ContactsController extends Controller{

    public function index(){
        $contact_info = $this->getInfo();

        return view('front.pages.contacts.index', compact(
          'contact_info'
        ));
    }

    public function getInfo(){
      $contact_info = new \stdClass;

      $contact_info->icon = '';
      $contact_info->title = 'o nosso email';
      $contact_info->text = 'info@ortopediaemcasa.pt';
      
      $contacts_info = [];

      for($i = 0; $i < 3; $i++){
        $contacts_info[] = $contact_info;
      }

      return $contacts_info;
    }

  }
?>
