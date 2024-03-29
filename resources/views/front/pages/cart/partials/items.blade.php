@if(sizeof($cart_items) <= 0)
    <div class="alert alert-info">
        @lang('app.empty-cart')
    </div>
@else
    <table class="table product__table">
        <thead>
            <tr>
                <th class="hidden-xs">
                    &nbsp;
                </th>
                <th>
                    @lang('app.items')
                </th>
                <th class="text-center">
                    @lang('app.qt')
                </th>
                <th class="text-center hidden-xs">
                    @lang('app.price')
                </th>
                <th class="text-right">
                    @lang('app.total')
                </th>
                <th>&nbsp;</th>
            </tr>
        </thead>

        <tbody>
            {{-- Begin: Item --}}
            @foreach($cart_items as $cart_item)
                <tr>
                    <td class="min-width-cell hidden-xs">
                        <div class="thumb" style="background-image:url({{ $cart_item->options->image_path }})"></div>
                    </td>
                    <td class="product__table-name">
                        <p>
                            <i>{{ $cart_item->options->category['name'] }}</i><br>
                            {{ $cart_item->name }}
                        </p>
                        @if(!empty($cart_item->options->attributes[0]) && count($cart_item->options->attributes))
                            <p>
                                @foreach($cart_item->options->attributes as $attribute)
                                    <b>{{ $attribute['name'] }}:</b> {{ $attribute['value'] }}<br>
                                @endforeach
                            </p>
                        @endif
                    </td>
                    <td class="text-center">
                        {!! Form::open(['action' => 'Store\CartController@update', 'class' => 'cart-qt-form']) !!}
                            {!! Form::hidden('row_id', $cart_item->rowId) !!}

                             <div class="minusplusnumber">
                                   <div id="field_container">
                                      {!! Form::input('number', 'quantity', $cart_item->qty, ['min' => 1, 'max' => $cart_item->options->stock, 'class' => 'product__input qt-input']) !!}
                                   </div>
                             </div>

                        {!! Form::close() !!}
                    </td>
                    <td class="text-center hidden-xs">
                        {{ \App\Lib\Store\Price::output($cart_item->priceTax) }}
                    </td>
                    <td class="text-right">
                        {{ \App\Lib\Store\Price::output($cart_item->total) }}
                    </td>
                    <td class="min-width-cell text-center">
                        {!! Form::open(['action' => 'Store\CartController@remove', 'class' => 'cart-delete-form']) !!}
                            {!! Form::hidden('row_id', $cart_item->rowId) !!}
                            <button class="btn btn-default" type="submit"><i class="zmdi zmdi-delete"></i></button>
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
            {{-- End: Item --}}
        </tbody>
    </table>
@endif
