@extends('front.layouts.app')

@section('meta')
    @include('front.layouts.meta', ['record' => $page, 'image_type' => ''])
@endsection

@section('content')
    <div class="container">
        <!-- Begin: Banner -->
        @include('front.components.categories-banner', [
            'categories' => $banner_categories
        ])
        <!-- End: Banner -->

        <div class="section">
            <div class="section__container">
                <h2 class="section__title--left">@lang('app.highlights')</h2>
                <a class="section__link--right" href="{{ urli18n('products') }}">@lang('app.see-all-products') <i class="zmdi zmdi-arrow-right-top"></i></a>
            </div>
            <!-- Begin: Products List -->
            <div class="product__list">
                @foreach($products as $product)
                <div class="product__collumn">
                    @include('front.components.product-card', [
                        'category' => $product->category,
                        'title' => $product->title,
                        'price' => $product->price,
                        'before_price' => $product->before_price
                    ])
                </div>
                @endforeach
            </div>
            <!-- End: Products List -->
        </div>

        @include('front.partials.brands-section')

        @include('front.partials.features-section')

        <div class="section">
            <!-- Begin: Newsletter Form -->
            @include('front.components.newsletter')
            <!-- End: Newsletter Form -->
        </div>
    </div>
@endsection
