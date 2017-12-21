@extends('/front/layouts/app')

@section('content')

    <div class="container">
        <!-- Begin: Banner -->
        @include('front.components.categories-banner', [
            'categories' => $banner_categories
        ])
        <!-- End: Banner -->
        
        <div class="section">
            <div class="section__container">
                <h2 class="section__title--left">Em destaque</h2>
                <a class="section__link--right" href="/">ver todos os projetos <i class="zmdi zmdi-arrow-right-top"></i></a>
            </div>
            <!-- Begin: Products List -->
            <div class="product__list">
                @foreach($products as $product)
                <div class="product__collumn">
                    @include('front.components.product-card', [
                        'category' => $product->category,
                        'title' => $product->title,
                        'price' => $product->price,
                        'before_price' => $product->before_price
                    ])
                </div>
                @endforeach
            </div>
            <!-- End: Products List -->
        </div>
        
        <div class="section">
            <div class="section__container">
                <h2 class="section__title">Oferecemos uma vasta gama de marcas...</h2>
            </div>
            <!-- Begin: Partners Banner -->
            @include('front.components.partners-slideshow', [

            ])
            <!-- End: Partners Banner -->
        </div>
        
        <div class="section">
            <div class="features">
                <h2 class="features__title">Porquê Ortopedia em Casa</h2>
                <h2 class="features__subtitle">artigos médicos, ortopédicos, de saúde e bem estar</h2>
                <div class="features__container">
                    <!-- Begin: Features Icon -->
                    @include('front.components.feature-icon', [
                        
                    ])
                    <!-- End: Features Icon -->
                </div>
            </div>
        </div>

        <div class="section">
            <!-- Begin: Newsletter Form -->
            @include('front.components.newsletter', [

            ])
            <!-- End: Newsletter Form -->
        </div>
    </div>
@endsection