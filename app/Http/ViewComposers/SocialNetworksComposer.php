<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;

use App\Models\SocialNetwork;

class SocialNetworksComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $social_networks = SocialNetwork::all();
        
        $view->with(
            compact(
                'social_networks'
            )
        );
    }
}
