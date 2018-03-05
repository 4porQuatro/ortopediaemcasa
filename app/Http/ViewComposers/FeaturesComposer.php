<?php

namespace App\Http\ViewComposers;

use App\Models\Pages\Article;
use Illuminate\View\View;

class FeaturesComposer
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
        $article = Article::find(18);

        $view->with(
            compact(
                'article'
            )
        );
    }
}
