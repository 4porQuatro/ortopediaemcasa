@php
    function siteUrl($loc, $lastmod = '', $priority = .9, $changeFreq = 'monthly')
    {
        return '<url>
            <loc>' . $loc . '</loc>
            <lastmod>' . $lastmod . '</lastmod>
            <changefreq>' . $changeFreq . '</changefreq>
            <priority>' . $priority . '</priority>
        </url>';
    }
@endphp
<?= '<?xml version="1.0" encoding="UTF-8"?>' ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @if($languages->count())
        @foreach ($languages as $language)
            {{ app()->setLocale($language->iso) }}

            <!-- Begin: Items -->
            @if($language->items->count())
                @foreach($language->items as $item)
                    {!! siteUrl(url($language->slug . '/' . trans('routes.product') . '/' . $item->slug), $item->updated_at, $item->priority) !!}
                @endforeach
            @endif
            <!-- End: Items -->

            <!-- Begin: Posts -->
            @if($language->posts->count())
                @foreach($language->posts as $post)
                    {!! siteUrl(url($language->slug . '/' . trans('routes.blog') . '/' . $post->slug), $post->updated_at, $post->priority) !!}
                @endforeach
            @endif
            <!-- End: Posts -->
        @endforeach
    @endif
</urlset>