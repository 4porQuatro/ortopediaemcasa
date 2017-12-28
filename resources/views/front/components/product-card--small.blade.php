<div class="col-xs-12 col-md-6 product">
    <a class="product-card product product--small" href="/products/detail">
        <div class="product-card__placeholder">
            <div class="product-card__image" style="background:url('{{$image}}') center center no-repeat; background-size: contain;"></div>
        </div>
        <div class="product-card__text">
            <p class="product-card__category">{{$category}}</p>
            <h3 class="product-card__name">{{$title}}</h3>
            <div class="product-card__price">
                <span class="product-card__price--label">a partir de...</span>
                <span class="product-card__price--before">{{$before_price}}</span>
                <span class="product-card__price--promotion">{{$price}}</span>
            </div>
        </div>
    </a>
</div>