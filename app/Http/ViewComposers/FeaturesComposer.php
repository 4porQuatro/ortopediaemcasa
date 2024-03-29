<?php

namespace App\Http\ViewComposers;

use App\Models\Pages\Article;
use App\Models\Topic;
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

        $features = Topic::where('topics_category_id', 1)->get();

        $view->with(
            compact(
                'article',
                'features'
            )
        );
    }
}
