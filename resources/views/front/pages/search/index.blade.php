@extends('front.layouts.app')

@section('content')
@include('front.components.breadcrumbs', [

])
<div class="container">
    <div class="section first">
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
    <div class="section">
            <div class="section__container">
                <h2 class="section__title">Oferecemos uma vasta gama de marcas...</h2>
            </div>
            <div class="partners">
                <div class="partners__slideshow">
                    @foreach($partners as $key => $partner)
                    <!-- Begin: Partners Banner -->
                        @include('front.components.partner-image', [
                            'image' => $partner->image
                        ])
                    <!-- End: Partners Banner -->
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
