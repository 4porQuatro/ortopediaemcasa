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
                <!-- Begin: User menu -->
                <div class="col-sm-3">
                    @include('front.pages.private-area.partials.user-menu')
                </div>
                <!-- End: User menu -->

                <div class="col-sm-9">
                    <div class="user-area__info">
                        @if($article = $page->articles->shift())
                            <div class="editable">
                                {!! $article->content !!}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
