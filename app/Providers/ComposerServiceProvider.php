<?php

namespace App\Providers;

use App\Http\ViewComposers\BrandsComposer;
use App\Http\ViewComposers\FeaturesComposer;
use App\Http\ViewComposers\NewsletterComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

use App\Http\ViewComposers\NavbarComposer;
use App\Http\ViewComposers\FooterComposer;
use App\Http\ViewComposers\UserMenuComposer;

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
            'front.layouts.nav', NavbarComposer::class
        );

        // Using class based composers...
        View::composer(
            'front.layouts.footer', FooterComposer::class
        );

        // Using class based composers...
        View::composer(
            'front.components.newsletter', NewsletterComposer::class
        );

        // Using class based composers...
        View::composer(
            'front.partials.features-section', FeaturesComposer::class
        );

        // Using class based composers...
        View::composer(
            'front.partials.brands-section', BrandsComposer::class
        );

        // Using class based composers...
        View::composer(
            'front.pages.private-area.partials.user-menu', UserMenuComposer::class
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
