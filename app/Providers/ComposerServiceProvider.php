<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

use App\Http\ViewComposers\NavbarComposer;
use App\Http\ViewComposers\FooterComposer;
use App\Http\ViewComposers\SocialNetworksComposer;

use Carbon\Carbon;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        // Using class based composers...
        View::composer(
            'layout.nav', NavbarComposer::class
        );

        // Using class based composers...
        View::composer(
            'layout.footer', FooterComposer::class
        );

        // Using class based composers...
        View::composer(
            'partials.social-networks', SocialNetworksComposer::class
        );
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
