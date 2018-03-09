@extends('front.layouts.app')

@section('meta')
    @include('front.layouts.meta', ['record' => $page, 'image_type' => ''])
@endsection

@section('content')
    <div class="container">
        <!-- Begin: Banner -->
        @include(
            'front.components.categories-banner',
            [
                'slides' => $banner_categories
            ]
        )
        <!-- End: Banner -->

        <!-- Begin: Products section -->
        @if(!empty($products))
            <div class="section">
                <div class="section__container">
                    <h2 class="section__title--left">@lang('app.highlights')</h2>
                    <a class="section__link--right" href="{{ urli18n('products') }}">@lang('app.see-all-products') <i class="zmdi zmdi-arrow-right-top"></i></a>
                </div>

                <!-- Begin: Products List -->
                <div class="product__list">
                    @foreach($products as $product)
                        <div class="product__collumn">
                            @include(
                                'front.components.product-card',
                                [
                                    'link' => urli18n('product', $product->slug),
                                    'image' => $product->getFirstImagePath('list'),
                                    'category' => $product->itemsCategory->title,
                                    'title' => $product->title,
                                    'price' => $product->price,
                                    'promo_price' => $product->promo_price
                                ]
                            )
                        </div>
                    @endforeach
                </div>
                <!-- End: Products List -->
            </div>
        @endif
        <!-- End: Products section -->

        @include('front.partials.brands-section')

        @include('front.partials.features-section')

        <div class="section">
            <!-- Begin: Newsletter Form -->
            @include('front.components.newsletter')
            <!-- End: Newsletter Form -->
        </div>
    </div>
@endsection
