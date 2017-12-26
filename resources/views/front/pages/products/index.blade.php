@extends('/front/layouts/app')

@section('content')
<div class="section first">
    <div class="container">
        <div class="row">
            
            <!-- Begin: Side Menu -->
            <div class="col-xs-12 col-md-4">
                @include('front.components.side-menu', [
                    
                ])
            </div>
            <!-- End: Side Menu -->

            <!-- Begin: Product List -->
            <div class="col-xs-12 col-md-7 col-md-offset-1" id="updatable">
                @foreach($products as $product)
                    @include('front.components.product-card--small', [
                        'category' => $product->category,
                        'title' => $product->title,
                        'price' => $product->price,
                        'before_price' => $product->before_price
                    ])
                @endforeach
            </div>
            <!-- End: Product List -->

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
</div>



@endsection