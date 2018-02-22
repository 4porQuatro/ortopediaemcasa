@component('mail::message')
<div class="text-center">
    {!! $email_message->message !!}

    <hr>

    <p>
        <b>@lang('app.date'):</b> {{ $order->created_at }}<br>
        <b>@lang('app.nr'):</b> {{ $order->id }}
    </p>
</div>
@endcomponent