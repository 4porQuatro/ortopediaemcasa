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

            {!! Form::open(['action' => "\App\Packages\ContactForm\ContactFormController@request", 'class' => "form-container"]) !!}
                @include('front.forms.contact-form')
            {!! Form::close() !!}

            <div class="contact-icons">
                <div class="row">
                    @foreach($contacts_info as $contact_info)
                        @include('front.components.contact-icon', [
                            'icon' => '',
                            'title' => $contact_info->title,
                            'text' => $contact_info->text
                        ])
                    @endforeach
                </div>
            </div>
        </div>


    </div>
@endsection
