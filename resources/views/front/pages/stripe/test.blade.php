@extends('front.layouts.app-alt')
@section('content')
    <div class="container">

    <form action="{{route('payment.stripe')}}" method="POST">
        {{csrf_field()}}
        <script
                src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                data-key="pk_test_yAOkKvlMbDsd7rl1O2Yij7JN00BNWpurFj"
                data-amount="999"
                data-name="ortopedia"
                data-description="Example charge"
                data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
                data-locale="auto">
        </script>
    </form>
    </div>
    @endsection


