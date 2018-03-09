<?php

namespace App\Http\ViewComposers;

use App\Models\Items\ItemsBrand;
use App\Models\Pages\Article;
use Illuminate\View\View;

class BrandsComposer
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
        $article = Article::find(19);

        $brands = ItemsBrand::where('highlight', 1)->get();

        $view->with(
            compact(
                'article',
                'brands'
            )
        );
    }
}
