@extends('front.layouts.app')

@section('meta')
    @include('front.layouts.meta', ['record' => $page, 'image_type' => ''])
@endsection

@section('content')
    @include(
        'front.components.breadcrumbs',
        [
            'crumbs' => [
                $page->title => ''
            ]
        ]
    )

    <div class="container">
        <div class="section first">
            @if($article = $page->articles->shift())
                @include(
                    'front.components.page-header',
                    [
                        'title' => $article->title,
                        'subtitle' => $article->subtitle
                    ]
                )
            @endif

            <div class="faqs__wrapper">
              {!! $article->content !!}
            </div>

        </div>
    </div>
@endsection
