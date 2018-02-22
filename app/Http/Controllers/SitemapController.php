<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Scopes\LanguageIdScope;
use Illuminate\Http\Request;

class SitemapController extends Controller
{
    /**
     * @return $this
     */
    public function index()
    {
        $languages = Language::with([
            'items' => function($query){
                $query->withoutGlobalScope(LanguageIdScope::class);
            },
            'posts' => function($query){
                $query->withoutGlobalScope(LanguageIdScope::class);
            }
        ])->get();

        return response()->view('sitemap',
            compact(
                'languages'
            )
        )->header('Content-Type', 'text/xml');
    }
}
