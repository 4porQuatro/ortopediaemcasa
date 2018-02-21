@extends('front.layouts.app')

@section('meta')
    @include('front.layouts.meta', ['record' => $page, 'image_type' => ''])
@endsection

@section('content')
    @include('front.components.breadcrumbs', [

    ])
    <div class="section first">
        <div class="container">
            <div class="row">

                <!-- Begin: Product Slide -->
                <div class="col-xs-12 col-md-6">
                    @include('front.components.product-slide', [
                        'products' => $products_slide
                    ])
                </div>
                <!-- End: Product Slide -->

                <!-- Begin: Product Description -->
                <div class="col-xs-12 col-md-6">
                    @include('front.components.product-description', [
                        'category' => $product_description->category,
                        'title' => $product_description->title,
                        'description' => $product_description->description
                    ])
                </div>
                <!-- End: Product Description -->

            </div>
            <form class="product__form">
                <div class="row">

                    <!-- Begin: Product Options -->
                    <div class="col-xs-12 col-md-6">
                        @include('front.components.product-options',[

                        ])
                    </div>
                    <!-- End: Product Options -->

                    <!-- Begin: Product Purchase -->
                    <div class="col-xs-12 col-md-6">
                        @include('front.components.product-purchase', [
                            'before_price' => $product_price->before,
                            'price' => $product_price->new
                        ])
                    </div>
                    <!-- End: Product Purchase -->

                </div>
            </form>

            <!-- Begin: Product Bottom Nav -->
            @include('front.components.product-bottom-nav', [

            ])
            <!-- End: Product Bottom Nav -->

            <!-- Begin: Product Advise Slideshow -->
            <div class="advise-container">
                <h2 class="slideshow-title">Veja também...</h2>
                <div class="slideshow-advise">
                    @foreach($products_advise as $product_advise)
                        @include('front.components.product-card--small', [
                            'category' => $product_advise->category,
                            'title' => $product_advise->title,
                            'image' => $product_advise->image,
                            'price' => $product_advise->price,
                            'before_price' => $product_advise->before_price
                        ])
                    @endforeach
                </div>
            </div>
            <!-- End: Product Advise Slideshow -->

            <div class="section">
            <!-- Begin: Newsletter Form -->
                @include('front.components.newsletter', [

                ])
            <!-- End: Newsletter Form -->
            </div>
        </div>
    </div>

@endsection
