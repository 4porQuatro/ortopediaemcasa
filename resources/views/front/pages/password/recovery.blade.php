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
                        'text_center' => true,
                        'title' => $article->title,
                        'subtitle' => $article->subtitle
                    ]
                )
            @endif

            {{-- Begin: Recover password form --}}
            {!! Form::open(['route' => 'password.email', 'autocomplete' => 'off']) !!}
                @include('front.forms.recover-password')
            {!! Form::close() !!}
            {{-- End: Recover password form --}}
        </div>
    </div>
@endsection
