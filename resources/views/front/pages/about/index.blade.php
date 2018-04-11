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
                    'front.components.about-banner',
                    [
                        'image' => $article->getFirstImagePath(),
                        'title' => $article->title,
                        'subtitle' => $article->subtitle
                    ]
                )
            @endif

            @if($article = $page->articles->shift())
                <div class="about__description editable">
                    <h2>{{ $article->title }}</h2>
                    {!! $article->content !!}
                </div>
            @endif
        </div>

        @include('front.partials.features-section')

        @include('front.partials.brands-section')

    </div>
@endsection
