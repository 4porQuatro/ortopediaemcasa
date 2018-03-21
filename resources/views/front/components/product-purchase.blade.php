<div class="product__purchase">
    <button type="submit" class="product__purchase--button">@lang('app.add')<i class="zmdi zmdi-shopping-cart"></i></button>

    <h4 class="product__purchase--price">{{ ($promo_price > 0) ? $promo_price : $price }}€</h4>

    @if($promo_price > 0)
        <h4 class="product__purchase--before">{{ $price  }}€</h4>
    @endif
</div>