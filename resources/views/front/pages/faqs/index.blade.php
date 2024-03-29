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
                @if(!$faqs->count())
                    @include('front.partials.no-records-found')
                @else
                    @foreach($faqs as $key => $faq)
                        @include('front.components.faqs', [
                            'question' => $faq->title,
                            'answer' => $faq->content
                        ])
                    @endforeach
                @endif
            </div>

        </div>
    </div>
@endsection
