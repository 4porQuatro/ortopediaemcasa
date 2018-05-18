@extends('front.layouts.app')

@section('meta')
    @include('front.layouts.meta', ['record' => $page, 'image_type' => ''])
@endsection

@section('content')
    @include(
        'front.components.breadcrumbs',
        [
            'crumbs' => [
                trans('app.private-area') => urli18n('user-welcome'),
                $page->title => ''
            ]
        ]
    )

    <div class="container">
        <div class="section first">
            @include(
                'front.components.page-header',
                [
                    'title' => trans('app.hello') . ' ' . auth()->user()->first_name
                ]
            )

            <div class="row">
                {{-- Begin: User menu --}}
                <div class="col-sm-3">
                    @include('front.pages.private-area.partials.user-menu')
                </div>
                {{-- End: User menu --}}

                <div class="col-sm-9">
                    {{-- Begin: Page article --}}
                    @if($article = $page->articles->shift())
                        <div class="editable">
                            <h3>{{ $article->title }}</h3>

                            {!! $article->content !!}
                        </div>
                    @endif
                    {{-- End: Page article --}}

                    {{-- Begin: Change password form --}}
                    {!! Form::model($user, ['action' => ['PrivateArea\UserPasswordController@update', $user->id], 'autocomplete' => 'off']) !!}
                        @include('front.forms.change-password')
                    {!! Form::close() !!}
                    {{-- Begin: Change password form --}}
                </div>
            </div>
        </div>
    </div>
@endsection
