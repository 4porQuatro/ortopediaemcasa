<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;

use App\Models\App\Contact;

class FooterComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     *
     * @return void
     */
    public function compose(View $view)
    {
        $contact = Contact::first();

        $view->with(
            compact(
                'contact'
            )
        );
    }
}
