
<!-- Begin: Product Slide Main Window -->
<div class="product__slide--main">
    @foreach($images as $key => $image)
    <div class="product__slide--main-placeholder full-slider-thumb" data-index="{{$key}}">
        <div class="product__image" style="background: url( {{$image}} ) center center no-repeat; background-size: contain;"></div>
    </div>
    @endforeach
</div>
<!-- End: Product Slide Main Window -->

<!-- Begin: Product Slide Nav Window -->
<div class="product__slide--nav">
    @foreach($images as $image)
    <div class="product__slide--nav-placeholder">
        <div class="product__image" style="background: url( {{$image}} ) center center no-repeat; background-size: contain;"></div>
    </div>
    @endforeach
</div>
<!-- End: Product Slide Nav Window -->

<div class="full-slider">
    <div class="full-slider-track">
        @foreach($images as $key => $image)
            <div class="full-slider-slide" style="background-image: url({{ $image }})" data-img="{{ $image }}"></div>
        @endforeach
    </div>

    <button class="full-slider-btn full-slider-btn--close zmdi zmdi-close"></button>
    <button class="full-slider-btn full-slider-btn--prev zmdi zmdi-long-arrow-left"></button>
    <button class="full-slider-btn full-slider-btn--next zmdi zmdi-long-arrow-right"></button>
</div>
