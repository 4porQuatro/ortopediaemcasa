@extends('front.layouts.app')

@section('meta')
    @include('front.layouts.meta', ['record' => $page, 'image_type' => ''])
@endsection

@section('content')
    @include('front.components.breadcrumbs')

    <!-- Begin: Login section -->
    <div class="container">
        <div class="section first">
            @if($article = $page->articles->shift())
                <h1 class="subsection__title text-center">{!! $article->title !!}</h1>
            @endif

            <!-- Begin: Login form -->
            {!! Form::open(['url' => urli18n('login'), 'method' => 'post', 'autocomplete' => 'off', 'class' => 'form-container']) !!}
                @include('front.forms.login')
            {!! Form::close() !!}
            <!-- End: Login form -->
        </div>
    </div>
    <!-- End: Login section -->

    <!-- Begin: Sign up section -->
    <div class="container">
        <div class="section first">
            @if($article = $page->articles->shift())
                <h2 class="subsection__subtitle text-center">{!! $article->title !!}</h2>
            @endif

            <!-- Begin: Sign up form -->
            {!! Form::open(['action' => 'Auth\RegisterController@register', 'autocomplete' => 'off', 'class' => 'form-container']) !!}
                @include('front.forms.sign-up', ['btn_name' => trans('app.sign-up')])
            {!! Form::close() !!}
            <!-- End: Sign up form -->
        </div>
    </div>
    <!-- End: Sign up section -->
@endsection
