@extends('front.layouts.app')

@section('meta')
    @include('front.layouts.meta', ['record' => $product, 'image_type' => 'list'])
@endsection

@section('content')
    @include(
        'front.components.breadcrumbs',
        [
            'crumbs' => [
                trans('app.products') => urli18n('products'),
                $product->itemCategory->root()->title . ' - ' . $product->itemCategory->title => urli18n('products') . '?cat=' . $product->itemCategory->id,
                $product->title => ''
            ]
        ]
    )

    <div class="section first">
        <div class="container">
            <div class="row">
                <!-- Begin: Product Slide -->
                <div class="col-xs-12 col-md-6">
                    @include(
                        'front.components.product-slide',
                        [
                            'images_path' => $product->getImagesUrl(),
                            'images' => $product->getImages('detail')
                        ]
                    )
                </div>
                <!-- End: Product Slide -->

                <!-- Begin: Product Description -->
                <div class="col-xs-12 col-md-6">
                    @include('front.components.product-description', [
                        'category' => $product->itemCategory->title,
                        'brand' => $product->itemBrand,
                        'title' => $product->title,
                        'description' => $product->content
                    ])
                </div>
                <!-- End: Product Description -->

            </div>

            {!! Form::open(['action' => 'Store\CartController@add', 'class' => "product__form", 'id' => 'add-to-cart-form']) !!}
                @if(count($errors->add_item) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->add_item->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row">
                    <!-- Begin: Product Options -->
                    <div class="col-xs-12 col-md-6">
                        @include('front.components.product-options')
                    </div>
                    <!-- End: Product Options -->

                    <!-- Begin: Product Purchase -->
                    <div class="col-xs-12 col-md-6">
                        @include(
                            'front.components.product-purchase',
                            [
                                'price' => $product->price,
                                'promo_price' => $product->promo_price
                            ]
                        )
                    </div>
                    <!-- End: Product Purchase -->
                </div>
            {!! Form::close() !!}

            <!-- Begin: Product Bottom Nav -->
            @include(
                'front.components.product-bottom-nav',
                [
                    'return_link' => urli18n('products') . '?cat=' . $product->itemCategory->id,
                    'facebook_link' => \App\Lib\SocialMedia::shareFacebookUrl(Request::url())
                ]
            )
            <!-- End: Product Bottom Nav -->

            <!-- Begin: Product Advise Slideshow -->
            @if($product->relatedItems->count())
                <div class="advise-container">
                    <h2 class="slideshow-title">@lang('app.see-also')</h2>
                    <div class="slideshow-advise">
                        @foreach($product->relatedItems as $product)
                            <div class="col-xs-12 col-md-6 product">
                                @include(
                                    'front.components.product-card--small',
                                    [
                                        'link' => urli18n('product', $product->slug),
                                        'image' => $product->getFirstImagePath('list'),
                                        'category' => $product->itemCategory->title,
                                        'title' => $product->title,
                                        'price' => $product->price,
                                        'promo_price' => $product->promo_price
                                    ]
                                )
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            <!-- End: Product Advise Slideshow -->
        </div>
    </div>
@endsection
