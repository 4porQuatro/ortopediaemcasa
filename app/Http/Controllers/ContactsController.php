<?php
  namespace App\Http\Controllers;

  use App\Models\App\Contact;
  use App\Models\Pages\Page;
  use Illuminate\Http\Request;

  class ContactsController extends Controller{

    public function index()
    {
        $page = Page::find(5);

        $contacts_info = Contact::first();

        return view(
            'front.pages.contacts.index',
            compact(
                'page',
                'contacts_info'
            )
        );
    }

  }
