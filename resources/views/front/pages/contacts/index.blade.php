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
            <h1 class="subsection__title text-center">{{ $article->title }}</h1>
            <h2 class="subsection__subtitle text-center">{{ $article->subtitle }}</h2>
        @endif

        @include('front.forms.contact-form', [])

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
    <div class="section">
        <!-- Begin: Newsletter Form -->
        @include('front.components.newsletter', [

        ])
        <!-- End: Newsletter Form -->
    </div>
</div>

@endsection
