@extends('front.layouts.app')

@section('meta')
    @include('front.layouts.meta', ['record' => $page, 'image_type' => ''])
@endsection

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
                <h1 class="subsection__title">{{ $article->title }}</h1>
                <h2 class="subsection__subtitle">{{ $article->subtitle }}</h2>

                <div class="subsection__description editable">
                    {!! $article->content !!}
                </div>
            @endif
        </div>
        <div class="section">
            <!-- Begin: Newsletter Form -->
            @include('front.components.newsletter', [

            ])
            <!-- End: Newsletter Form -->
        </div>
    </div>
@endsection
