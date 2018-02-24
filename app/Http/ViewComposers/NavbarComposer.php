<?php

namespace App\Http\ViewComposers;

use App\Lib\AppData;
use App\Models\Language;
use App\Repositories\ItemRepository;
use Illuminate\View\View;


class NavbarComposer
{
    protected $item_repo;

    public function __construct(ItemRepository $item_repo)
    {
        $this->item_repo = $item_repo;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $item_types = $this->item_repo->getTree();

        $languages = Language::where('iso', '!=', config('app.locale'))->get();

        $user_menus = AppData::getUserMenus();

        $view->with(
            compact(
                'item_types',
                'languages',
                'user_menus'
            )
        );
    }
}
