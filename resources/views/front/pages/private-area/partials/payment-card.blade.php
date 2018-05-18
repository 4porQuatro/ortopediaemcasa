<div class="payment-card {{ $card_modifier or '' }}">
    {{-- Begin: Title --}}
    <h3 class="payment-card-title">{{ strip_tags($name) }}</h3>
    {{-- End: Title --}}

    {{-- Begin: Description --}}
    <div class="payment-card-description">
        {{ strip_tags($description) }}
    </div>
    {{-- End: Description --}}

    {{-- Begin: Note --}}
    <div class="payment-card-note">
        @if(!empty($note))
            {!! $note !!}
        @endif

        @if($id == 3 && $order->paymentReference)
            <p>
                @lang('app.entity'): {{  $order->paymentReference->entity }}<br>
                @lang('app.reference'): {{  $order->paymentReference->reference }}<br>
                @lang('app.amount'): {{  \App\Lib\Store\Price::output($order->paymentReference->amount) }}
            </p>
        @endif
    </div>
    {{-- End: Note --}}

    {{-- Begin: Button --}}
    <div class="payment-card-btn">
        @if($id == 1)
            <a class="btn-square btn-square-dark" href="{{ action('\App\Packages\PayPal\PayPalPaymentController@expressCheckout', ['order_id' => $order->id]) }}">@lang('app.pay-with-paypal')</a>
        @elseif($id == 4)
            <a class="btn-square btn-square-dark" href="{{ urli18n('contacts') }}">@lang('app.more-info')</a>
        @endif
    </div>
    {{-- End: Button --}}
</div>