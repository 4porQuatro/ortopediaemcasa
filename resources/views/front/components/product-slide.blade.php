
<!-- Begin: Product Slide Main Window -->
<div class="product__slide--main">
    @foreach($products as $main)
        <div class="product__slide--main-placeholder full-slider-thumb">
            <div class="product__image" style="background: url('{{$main->image}}') center center no-repeat; background-size: contain;"></div>
        </div>
    @endforeach
</div>
<!-- End: Product Slide Main Window -->

<!-- Begin: Product Slide Nav Window -->
<div class="product__slide--nav">
    @foreach($products as $nav)
    <div class="product__slide--nav-placeholder">
        <div class="product__image" style="background: url('{{$nav->image}}') center center no-repeat; background-size: cover;"></div>
    </div>
    @endforeach
</div>
<!-- End: Product Slide Nav Window -->
