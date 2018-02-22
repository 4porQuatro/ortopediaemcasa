@component('mail::message')
<div class="text-center">
{!! $email_message->message !!}

<hr>

<p>
    <b>@lang('app.date'):</b> {{ $order->created_at }}<br>
    <b>@lang('app.nr'):</b> {{ $order->id }}
</p>
</div>

<table class="items-table">
    <thead>
        <tr>
            <th align="left">@lang('app.items')</th>
            <th>@lang('app.color')</th>
            <th>@lang('app.size')</th>
            <th>@lang('app.qt')</th>
            <th align="right">@lang('app.price')</th>
        </tr>
    </thead>

    <tbody>
        @foreach($order->items as $item)
            @php
                $attributes = json_decode($item->attributes);
            @endphp
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ $attributes->color->name }}</td>
                <td align="center">{{ $attributes->size->name }}</td>
                <td align="center">{{ $item->quantity }}</td>
                <td align="right">{{ \App\Lib\Store\Price::output($item->price) }}</td>
            </tr>
        @endforeach
    </tbody>

    <tfooter>
        <tr>
            <th colspan="4" align="right">@lang('app.items')</th>
            <th align="right">{{ \App\Lib\Store\Price::output($order->items_total) }}</th>
        </tr>
        <tr>
            <th colspan="4" align="right">@lang('app.shipping')</th>
            <th align="right">{{ \App\Lib\Store\Price::output($order->shipping_cost) }}</th>
        </tr>
        <tr>
            <th colspan="4" align="right">@lang('app.vat-number')</th>
            <th align="right">{{ \App\Lib\Store\Price::output($order->taxes) }}</th>
        </tr>
        <tr>
            <th colspan="4" align="right">@lang('app.voucher-discount')</th>
            <th align="right">{{ \App\Lib\Store\Price::output($order->voucher_discount) }}</th>
        </tr>
        <tr>
            <th colspan="4" align="right">@lang('app.points-discount')</th>
            <th align="right">{{ \App\Lib\Store\Price::output($order->points_discount) }}</th>
        </tr>
    </tfooter>
</table>
@endcomponent