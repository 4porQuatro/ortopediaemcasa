<a class="product-card" href="/">
    <div class="product-card__placeholder">
        <div class="product-card__image" style="background:url('/front/images/products/product_1.jpg') center center no-repeat; background-size: contain;"></div>
    </div>
    <div class="product-card__text">
        <p class="product-card__category">{{$category}}</p>
        <h3 class="product-card__name">{{$title}}</h3>
        <div class="product-card__price">
            <span class="product-card__price--label">a partir de...</span>
            <span class="product-card__price--box">
                <span class="product-card__price--before">{{$before_price}}</span>
                <span class="product-card__price--promotion">{{$price}}</span>
            </span>
        </div>
    </div>
</a>
