@extends('front.layouts.app')

@section('meta')
    @include('front.layouts.meta', ['record' => $page, 'image_type' => ''])
@endsection

@section('content')
    @include('front.components.breadcrumbs', [

    ])
    <div class="container">
        <div class="section first">
            @if($article = $page->articles->shift())
                <h1 class="subsection__title">{{ $article->title }}</h1>
                <h2 class="subsection__subtitle">{{ $article->subtitle }}</h2>
            @endif

            <div class="faqs__wrapper">
                @if(!$faqs->count())
                    @include('front.partials.no-records-found')
                @else
                    @foreach($faqs as $faq)
                        @include('front.components.faqs', [
                            'question' => $faq->title,
                            'answer' => $faq->content
                        ])
                    @endforeach
                @endif
            </div>

        </div>
        <div class="section">
            <!-- Begin: Newsletter Form -->
            @include('front.components.newsletter', [

            ])
            <!-- End: Newsletter Form -->
        </div>
    </div>
@endsection
