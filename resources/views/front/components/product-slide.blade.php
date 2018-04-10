<!-- Begin: Product Slide Main Window -->
<div class="product__slide--main">
    @foreach($images as $image)
        <div class="product__slide--main-placeholder full-slider-thumb">
            <div class="product__image" style="background: url('{{ $images_path . '/' . $image->source}}') center center no-repeat; background-size: contain;"></div>
        </div>
    @endforeach
</div>
<!-- End: Product Slide Main Window -->

<!-- Begin: Product Slide Nav Window -->
<div class="product__slide--nav">
    @foreach($images as $image)
    <div class="product__slide--nav-placeholder">
        <div class="product__image" style="background: url('{{ $images_path . '/' . $image->source}}') center center no-repeat; background-size: contain;"></div>
    </div>
    @endforeach
</div>
<!-- End: Product Slide Nav Window -->
