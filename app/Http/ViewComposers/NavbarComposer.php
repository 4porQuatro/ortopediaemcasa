<?php

namespace App\Http\ViewComposers;

use App\Lib\AppData;
use App\Models\Language;
use Illuminate\View\View;


class NavbarComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {

        $languages = Language::where('iso', '!=', config('app.locale'))->get();

        $user_menus = AppData::getUserMenus();

        $view->with(
            compact(
                'languages',
                'user_menus'
            )
        );
    }
}
