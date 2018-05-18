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

    <div class="container">
        <div class="section first">
            @if($article = $page->articles->shift())
                @include(
                    'front.components.page-header',
                    [
                        'text_center' => true,
                        'title' => $article->title
                    ]
                )
            @endif

			{{-- Begin: Items list --}}
			<div class="section__content">
				{{-- Begin: Errors --}}
				@if (count($errors->order) > 0)
					<div class="alert alert-danger">
						<ul>
							@foreach($errors->order->all() as $error)
								<li>{{ $error }}</li>
							@endforeach
						</ul>
					</div>
				@endif
				{{-- End: Errors --}}

				@if($cart_items->count())
					<div id="cart-items-displayer"></div>
				@else
					<div class="alert alert-info text-center">
						@lang('app.add-item')
					</div>
				@endif
			</div>
			{{-- End: Items list --}}
		</div>
	</div>

	@if($cart_items->count())
		{!! Form::open(['action' => ['Store\OrderController@store']]) !!}
			{{-- Begin: Shipping methods --}}
			<section class="section">
				<div class="container">
					<div class="section__header">
						@php
							$article = $page->articles->shift();
						@endphp

						@if($article)
							<h2 class="title text-center">{!! $article->title !!}</h2>
						@endif
					</div>

					<div class="section__content">
						{{-- Begin: Shipping options --}}
						<div id="checkout-shipping-displayer"></div>
						{{-- End: Shipping options --}}
					</div>
				</div>
			</section>
			{{-- Begin: Shipping methods --}}

			{{-- Begin: User data --}}
			@if($user)
				<section class="section">
					<div class="container">
						<div class="section__header">
							@php
								$article = $page->articles->shift();
							@endphp

							@if($article)
								<h2 class="title text-center">{!! $article->title !!}</h2>
							@endif
						</div>

						<div class="section__content">
							<div class="row">
								{{-- Begin: User billing info --}}
								<div class="col-sm-6">
									<h3>@lang('app.billing-info')</h3>

									<br>

									<p class="text">
										{{ $user->billing_name }}<br>
										{{ $user->billing_address }}<br>
										{{ $user->billing_zip_code }} {{ $user->billing_city }} - {{ $user->billingCountry->name }}
									</p>

									<p class="text">
										<b>@lang('app.vat-number'):</b> {{ $user->vat_number }}
									</p>
								</div>
								{{-- End: User billing info --}}

								{{-- Begin: User shipping info --}}
								<div class="col-sm-6">
									<h3>@lang('app.shipping-info')</h3>

									<br>

									<p class="text">
										{{ $user->shipping_name }}<br>
										{{ $user->shipping_address }}<br>
										{{ $user->shipping_zip_code }} {{ $user->shipping_city }} - {{ $user->shippingCountry->name }}
									</p>
								</div>
								{{-- End: User shipping info --}}
							</div>
						</div>
					</div>
				</section>
			@endif
			{{-- End: User data --}}

			<section class="section">
				<div class="container">
					<div class="payment-info">
						<div class="cart-summary">
							{{-- Begin: Cart summary --}}
							<div id="cart-summary-displayer"></div>
							{{-- End: Cart summary --}}
						</div>
					</div>
				</div>
			</section>

			<section class="section">
				<div class="container">
					<div class="text-center">
						@if(auth()->check())
							<button type="submit" class="btn btn-default btn-lg"><i class="zmdi zmdi-shopping-basket"></i> @lang('app.conclude-purchase')</button>
						@else
							<a class="btn btn-default btn-lg" href="{{ urli18n('login') }}"><i class="zmdi zmdi-account"></i> @lang('app.login')</a>
						@endif
					</div>
				</div>
			</section>
		{!! Form::close() !!}
	@endif
@endsection

@push('scripts')
	<script src="{{ asset('front/js/cart.js') }}"></script>
	<script>
        initCheckout('{{ url(config('app.locale_prefix')) }}');
	</script>
@endpush
