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

        <div class="section">
            <div class="section__container">
                <h2 class="section__title">Oferecemos uma vasta gama de marcas...</h2>
            </div>
            <div class="partners">
                <div class="partners__slideshow">
                    @foreach($partners as $key => $partner)
                    <!-- Begin: Partners Banner -->
                        @include('front.components.partner-image', [
                            'image' => $partner->image
                        ])
                    <!-- End: Partners Banner -->
                    @endforeach
                </div>
            </div>
        </div>

        <div class="section">
            <!-- Begin: Newsletter Form -->
            @include('front.components.newsletter')
            <!-- End: Newsletter Form -->
        </div>
    </div>
@endsection
