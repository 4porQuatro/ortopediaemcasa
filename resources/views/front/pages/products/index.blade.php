@extends('front.layouts.app')

@section('meta')
    @include('front.layouts.meta', ['record' => $page, 'image_type' => ''])
@endsection

@section('content')
    @include(
        'front.components.breadcrumbs',
        [
            'crumbs' => [
                $page->title => ''
            ]
        ]
    )

    <div class="section first">
        <div class="container">
            <div class="row">
                <!-- Begin: Side Menu -->
                <div class="col-xs-12 col-md-3 col-xlg-2">
                    @include(
                        'front.partials.multiLevelMenu',
                        [
                            'html' => $menu_html
                        ]
                    )
                </div>
                <!-- End: Side Menu -->

                <!-- Begin: Product List -->
                <div class="col-xs-12 col-md-9 col-xlg-10" id="updatable">
                    @if(!$products->count())
                        @include('front.partials.no-records-found')
                    @else
                        <div class="row">
                            @foreach($products as $product)
                                <div class="col-xs-12 col-md-6 col-lg-4 product">
                                    @include(
                                        'front.components.product-card--small',
                                        [
                                            'link' => urli18n('product', $product->slug),
                                            'category' => $product->itemsCategory->title,
                                            'title' => $product->title,
                                            'image' => $product->getFirstImagePath('list'),
                                            'price' => $product->price,
                                            'promo_price' => $product->promo_price
                                        ]
                                    )
                                </div>
                            @endforeach
                        </div>

                        {{ $products->appends(['search' => request('search'), 'cat' => request('cat')])->links() }}
                    @endif
                </div>
                <!-- End: Product List -->
            </div>

            @include('front.partials.brands-section')

            <div class="section">
                <!-- Begin: Newsletter Form -->
                @include('front.components.newsletter')
                <!-- End: Newsletter Form -->
            </div>
        </div>
    </div>
@endsection
