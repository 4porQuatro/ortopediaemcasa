<a class="product-card product product--small" href="{{ $link }}" style="margin-top: 0; margin-bottom: 30px;">
    <div class="product-card__placeholder">
        <div class="product-card__image"
             style="background:url('{{$image}}') center center no-repeat; background-size: contain;"></div>
    </div>

    <div class="product-card__text">
        <p class="product-card__category">{{$category}}</p>
        <h3 class="product-card__name">{{$title}}</h3>

        <div class="product-card__price">
            <span class="product-card__price--label">@lang('app.prices-since')</span>

            @if($promo_price > 0)
                <span class="product-card__price--before">{{ $price }}€</span>
            @endif

            <span class="product-card__price--promotion">{{ ($promo_price > 0) ? $promo_price : $price }}€</span>
        </div>
    </div>
</a>