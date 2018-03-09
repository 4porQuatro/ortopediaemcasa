<div class="product__bottom-nav">
    <div class="product__bottom-nav-table">
        <div class="product__bottom-nav-cell">
            <p>
                @lang('app.share-product'):
                <a href="{{ $facebook_link }}" target="_blank" class="zmdi zmdi-facebook-box"></a>
            </p>
        </div>

        <div class="product__bottom-nav-cell">
            <a class="product__bottom-nav-link" href="{{ $return_link }}">@lang('app.return-to-list')</a>
        </div>
    </div>
</div>
