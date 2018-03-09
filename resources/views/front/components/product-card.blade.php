<a class="product-card" href="{{ $link }}">
    <div class="product-card__placeholder">
        <div class="product-card__image" style="background:url('{{ $image }}') center center no-repeat; background-size: contain;"></div>
    </div>

    <div class="product-card__text">
        <p class="product-card__category">{{$category}}</p>
        <h3 class="product-card__name">{{$title}}</h3>

        <div class="product-card__price">
            <span class="product-card__price--label">@lang('app.prices-since')</span>

            <span class="product-card__price--box">
                @if($promo_price > 0)
                    <span class="product-card__price--before">{{ $price }}€</span>
                @endif
                <span class="product-card__price--promotion">{{ ($promo_price > 0) ? $promo_price : $price }}€</span>
            </span>
        </div>
    </div>
</a>
