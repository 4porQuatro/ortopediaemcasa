<span class="product__category">{{$category}}</span>

<h1 class="product__title">{{$title}}</h1>

<img src="{{ $brand->getFirstImagePath() }}" alt="{{ $brand->title }}" style="max-width: 100%; max-height: 40px;">

<div class="product__description editable">
    {!! $description !!}
</div>
