@component('mail::message')
<div class="text-center">
    {!! $email_message->message !!}

    <hr>

    <p>
        <b>@lang('app.date'):</b> {{ $order->created_at }}<br>
        <b>@lang('app.nr'):</b> {{ $order->id }}<br>
        <b>@lang('app.name'):</b> {{ $order->user->billing_name }}<br>
        <b>Metodo de pagamento:</b> <br>
    </p>

    <table class="items-table">
        <thead>
        <tr>
            <th align="left">@lang('app.items')</th>
            <th align="center">@lang('app.attributes')</th>
            <th align="center">@lang('app.quantity')</th>
            <th align="right">@lang('app.price')</th>
        </tr>
        </thead>

        <tbody>
        @php
            $items_total = 0;
        @endphp
        @foreach($order->items as $item)
            <tr>
                @php
                    $item_data = json_decode($item->attributes);
                    $items_sub_total = $item->price * $item->quantity;
                    $items_total += $items_sub_total;
                @endphp
                <td>
                    {{ $item->name }}
                    <i>{{$item_data->category->name}}</i>
                </td>

                <td align="center">
                    @if(!empty($item_data->attributes))
                        @foreach($item_data->attributes as $attribute)
                            <strong>{{$attribute->name}}: </strong>{{$attribute->value}}<br>
                        @endforeach
                    @endif
                </td>
                <td>
                    {{$item->quantity}}
                </td>
                <td align="right">{{ \App\Lib\Store\Price::output($item->price) }}</td>
            </tr>
        @endforeach
        </tbody>

        <tfooter>
            <tr>
                <th colspan="4" align="right">@lang('app.items')</th>
                <th align="right">{{ \App\Lib\Store\Price::output($items_total) }}</th>
            </tr>

            <tr>
                <th colspan="4" align="right">IVA</th>
                <th align="right">{{ \App\Lib\Store\Price::output($order->taxes) }}</th>
            </tr>

            <tr>
                <th colspan="4" align="right">@lang('app.shipping')</th>
                <th align="right">{{ \App\Lib\Store\Price::output($order->shipping_cost) }}</th>
            </tr>

            <tr>
                <th colspan="4" align="right">@lang('app.voucher-discount')</th>
                <th align="right">{{ \App\Lib\Store\Price::output($order->voucher_discount) }}</th>
            </tr>
        <!--
        <tr>
            <th colspan="4" align="right">@lang('app.points-discount')</th>
            <th align="right">{{ \App\Lib\Store\Price::output($order->points_discount) }}</th>
        </tr>
        -->
            <tr>
                <th colspan="4" align="right"><b>Total</b></th></th>
                <th align="right">{{ \App\Lib\Store\Price::output($order->total) }}</th>
            </tr>
        </tfooter>




    </table>

    <div style="margin-top:20px">
        {{-- Begin: User billing info --}}
        <div class="col-sm-6">
            <div class="white-box">
                <h3 class="subsection__subtitle">@lang('app.billing-info')</h3>

                <br>
                <div class="text-wrapper">

                    <p class="text">
                        {{ auth()->user()->billing_name }}<br>
                        {{ auth()->user()->billing_address }}<br>
                        {{ auth()->user()->billing_zip_code }} {{ auth()->user()->billing_city }} - {{ auth()->user()->billingCountry->name }}
                    </p>

                    <p class="text">
                        <b>@lang('app.vat-number'):</b> {{ auth()->user()->vat_number }}
                    </p>
                </div>

            </div>
        </div>
        {{-- End: User billing info --}}

        {{-- Begin: User shipping info --}}
        <div class="col-sm-6">
            <div class="white-box">

                <h3  class="subsection__subtitle">@lang('app.shipping-info')</h3>

                <br>

                <p class="text text-wrapper">
                    {{ auth()->user()->shipping_name }}<br>
                    {{ auth()->user()->shipping_address }}<br>
                    {{ auth()->user()->shipping_zip_code }} {{ auth()->user()->shipping_city }} - {{ auth()->user()->shippingCountry->name }}
                </p>
            </div>
        </div>
        {{-- End: User shipping info --}}
    </div>


</div>
@endcomponent
