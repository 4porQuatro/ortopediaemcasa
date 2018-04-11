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
            @if(!empty($brands))
                <div class="clearfix">
                    <div class="dropdown pull-right" style="margin-bottom: 15px;">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @lang('app.brands')
                        </button>

                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item products_filters_option" data-filter="brand" data-id="%" href="{{ urli18n('products') }}" style="padding: 1px 12px; display: block;">@lang('app.see-all')</a>
                            @foreach($brands as $brand)
                                <a class="dropdown-item products_filters_option" data-filter="brand" data-id="{{ $brand->id }}" href="{{ urli18n('products') }}?brand={{ $brand->id }}" style="padding: 1px 12px; display: block;">{{ $brand->title }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

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
                                            'category' => $product->itemCategory->title,
                                            'title' => $product->title,
                                            'image' => $product->getFirstImagePath('list'),
                                            'price' => $product->price,
                                            'promo_price' => $product->promo_price
                                        ]
                                    )
                                </div>
                            @endforeach
                        </div>

                        {{ $products->appends(['search' => request('search'), 'category' => request('category'), 'brand' => request('brand')])->links() }}
                    @endif
                </div>
                <!-- End: Product List -->
            </div>

            @include('front.partials.brands-section')

        </div>
    </div>

    <!-- Begin: Hidden filters form -->
    {!! Form::open(['action' => "ProductsController@index", 'method' => "GET", 'class' => "hidden", 'id' => "products_filters_form"]) !!}
        {!! Form::hidden('brand') !!}
        {!! Form::hidden('category') !!}
    {!! Form::close() !!}
    <!-- End: Hidden filters form -->
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded",function() {
            let selectors_options = document.querySelectorAll('.products_filters_option'),
                form = document.getElementById('products_filters_form');

            function setFiltersForm(event) {
                event.preventDefault();

                let option = event.target,
                    filter = option.dataset.filter,
                    id = option.dataset.id,
                    input = form.querySelector('[name="' + filter + '"]');

                input.value = id;

                form.submit();
            }

            selectors_options.forEach(option => {
                option.addEventListener('click', setFiltersForm);
            });
        });
    </script>
@endpush
