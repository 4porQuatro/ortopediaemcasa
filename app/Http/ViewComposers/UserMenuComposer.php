<?php

namespace App\Http\ViewComposers;

use App\Lib\AppData;
use Illuminate\View\View;


class UserMenuComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $user_menus = AppData::getUserMenus();

        $view->with(
            compact(
                'user',
                'user_menus'
            )
        );
    }
}
