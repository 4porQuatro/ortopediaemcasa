<div class="container container--shrink">


<div class="white-box payment-card {{ $card_modifier or '' }}">

    <!-- Begin: Title -->
    <h3 class="payment-card-title subsection__subtitle">{{ strip_tags($name) }}</h3>
     @if(!empty($image))
         <img height="30" src="{{$image}}">
     @endif
    <!-- End: Title -->

    <!-- Begin: Description -->
    <div class="payment-card-description">
        {{ strip_tags($description) }}
    </div>
    <!-- End: Description -->

    <!-- Begin: Note -->
    <div class="payment-card-note">
        @if(!empty($note))
            {!! $note !!}
        @endif

        @if($id == 3 && $order->paymentReference)
            <p style="text-align: center">
                @lang('app.entity'): {{  $order->paymentReference->entity }}<br>
                @lang('app.reference'): {{  $order->paymentReference->reference }}<br>
                @lang('app.amount'): {{  \App\Lib\Store\Price::output($order->paymentReference->amount) }}
            </p>
        @endif

    </div>
    <!-- End: Note -->

    <!-- Begin: Button -->
    <div class="payment-card-btn" style="text-align: center">
        @if($id == 1)
            <a class="btn-square btn-square-dark" href="{{ action('\App\Packages\PayPal\PayPalPaymentController@expressCheckout', ['order_id' => $order->id]) }}">
                <img height="30" src="{{url('/images/paypal.png')}}">
            </a>
        @elseif($id == 4)
            <a class="btn-square btn-square-dark" href="{{ urli18n('contacts') }}">@lang('app.more-info')</a>

        @elseif($id == 5)
            <form action="{{route('payment.stripe')}}" method="POST">
                {{csrf_field()}}
                <input type="hidden" name="order" value="{{$order->id}}">
                <script
                        src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                        data-key="{{config('services.stripe.key')}}"
                        data-amount="{{$order->total*100}}"
                        data-name="Ortopedia em casa"
                        data-currency="eur"
                        data-description="Pagamento"
                        data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
                        data-locale="auto">
                </script>
            </form>
        @endif
    </div>
    <!-- End: Button -->
</div>
</div>
