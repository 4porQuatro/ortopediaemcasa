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
                    <!-- Begin: Page article -->
                    @if($article = $page->articles->shift())
                        <div class="editable">
                            <h3>{{ $article->title }}</h3>

                            {!! $article->content !!}
                        </div>
                    @endif
                    <!-- End: Page article -->

                    @if(!auth()->user()->items->count())
                        <div class="alert alert-info">
                            @lang('app.empty-wishlist')
                        </div>
                    @else
                        <table class="cart-table table">
                            <tbody>
                                <tr>
                                    <th>
                                        <p class="cart-table__title">Artigos</p>
                                    </th>
                                </tr>

                                <!-- Begin: Item list -->
                                @foreach(auth()->user()->items as $item)
                                    <tr>
                                        <td>
                                            <div class="cart-table__image" style="background-image:url({{ asset($item->getFirstImagePath('list')) }})"></div>

                                            <div class="cart-table__name">
                                                <p class="cart-table__subtitle">Ref. {{ $item->reference }}</p>
                                                <p class="text text--italic">{{ $item->title }}</p>
                                                @if($item->promo_price > 0)
                                                    <h4 class="product-info__price product-info__price--old">{{ $item->formatted_price }}</h4>
                                                    <h4 class="product-info__price">{{ $item->formatted_promo_price }}</h4>
                                                @else
                                                    <h4 class="product-info__price">{{ $item->formatted_price }}</h4>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ urli18n('product', $item->slug) }}" class="btn-border cart-table__add text-center">@lang('app.add')<span class="icon icon--cart"></span></a>
                                        </td>
                                        <td>
                                            {!! Form::open(['method' => 'DELETE', 'action' => ['Store\WishlistController@destroy', $item->id]]) !!}
                                                <button type="submit" class="cart-table__remove icon icon--close"></button>
                                            {!! Form::close() !!}
                                        </td>
                                    </tr>
                                @endforeach
                                <!-- End: Items list -->
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
