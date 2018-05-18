@extends('front.layouts.app')

@section('meta')
    @include('front.layouts.meta', ['record' => $page, 'image_type' => ''])
@endsection

@section('content')
    @include(
        'front.components.breadcrumbs',
        [
            'crumbs' => [
                trans('app.private-area') => urli18n('user-welcome'),
                $page->title => ''
            ]
        ]
    )

    <div class="container">
        <div class="section first">
            @include(
                'front.components.page-header',
                [
                    'title' => trans('app.hello') . ' ' . auth()->user()->first_name
                ]
            )

            <div class="row">
                {{-- Begin: User menu --}}
                <div class="col-sm-3">
                    @include('front.pages.private-area.partials.user-menu')
                </div>
                {{-- End: User menu --}}

                <div class="col-sm-9">
                    {{-- Begin: Page article --}}
                    @if($article = $page->articles->shift())
                        <div class="editable">
                            <h3>{{ $article->title }}</h3>

                            {!! $article->content !!}
                        </div>
                    @endif
                    {{-- End: Page article --}}

                    @if(!$user->orders->count())
                        <div class="alert alert-info">
                            @lang('app.no-orders-placed')
                        </div>
                    @else
                        {{-- Begin: Orders list --}}
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            @foreach($user->orders as $key => $order)
                                {{-- Begin: Panel --}}
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="heading{{ $key }}">
                                        <h4 class="panel-title">
                                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{ $key }}" aria-expanded="true" aria-controls="collapse{{ $key }}">
                                                @lang('app.order') #{{ $order->id }} | <span class="{{ $order->state->id == 2 ? ' text-info' : ' text-danger' }}">{{ $order->state->title }}</span>
                                            </a>
                                        </h4>
                                    </div>

                                    {{-- Begin: Collapsable area --}}
                                    <div id="collapse{{ $key }}" class="panel-collapse collapse {{ ($key == 0) ?  'in' : '' }}" role="tabpanel" aria-labelledby="heading{{ $key }}">
                                        {{-- Begin: Panel body --}}
                                        <div class="panel-body">
                                            {{-- Begin: Items --}}
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th class="hidden-xs">&nbsp;</th>
                                                        <th>@lang('app.items')</th>
                                                        <th class="text-center">@lang('app.qt')</th>
                                                        <th class="text-right">@lang('app.price')</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @foreach($order->items as $item)
                                                        @php
                                                            $item_data = json_decode($item->attributes);
                                                        @endphp
                                                        <tr>
                                                            <td class="min-width-cell hidden-xs">
                                                                <div class="thumb pull-left" style="background-image: url({{ $item->image_url }});"></div>
                                                            </td>
                                                            <td>
                                                                <p>
                                                                    <b>{{ $item->name }}</b><br>
                                                                    <i>{{ $item_data->category->name }}</i>
                                                                </p>
                                                                <p>
                                                                    @if(!empty($item_data->attributes))
                                                                        @foreach($item_data->attributes as $attribute)
                                                                            <b>{{ $attribute->name }}:</b> {{ $attribute->value }}<br>
                                                                        @endforeach
                                                                    @endif
                                                                </p>
                                                            </td>
                                                            <td class="text-center">
                                                                {{ $item->quantity }}
                                                            </td>
                                                            <td class="min-width-cell text-right">
                                                                {{ $item->taxed_price }} €
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>

                                                <tfoot>
                                                    <tr>
                                                        <td class="hidden-xs">&nbsp;</td>
                                                        <td class="text-right" colspan="2">
                                                            Items:<br>
                                                            Envio:<br>
                                                            {{--@lang('app.voucher-discount'):<br>--}}
                                                            {{--@lang('app.points-discount'):<br>--}}
                                                            <b>Total:</b>
                                                        </td>
                                                        <td class="min-width-cell text-right">
                                                            {{ $order->items_total }} €<br>
                                                            {{ $order->shipping_cost }} €<br>
                                                            {{--{{ ($order->voucher_discount > 0) ? '-' : '' }}{{ $order->voucher_discount }} €<br>--}}
                                                            {{--{{ ($order->points_discount > 0) ? '-' : '' }}{{ $order->points_discount }} €<br>--}}
                                                            <b>{{ $order->total }} €</b>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                            {{-- End: Items --}}

                                            <hr>

                                            {{-- Begin: Order data --}}
                                            <p>
                                                <b>@lang('app.date'):</b> {{ $order->created_at->formatLocalized('%d %B %Y') }}<br>
                                                <b>@lang('app.state'):</b> <span class="{{ $order->state->id == 2 ? ' text-info' : ' text-danger' }}">{{ $order->state->title }}</span><br>
                                                {{-- <b>@lang('app.points-earned'):</b> <span class="{{ $order->state->id == 2 ? ' text-info' : ' text-danger' }}">{{ $order->points_earned }} {{ $order->points_earned == 1 ? 'ponto' : 'pontos' }}{{ $order->state->id == 2 ? '' : ' *' }}</span><br> --}}
                                                <b>@lang('app.shipping-method'):</b> {{ $order->shipping_method }}
                                            </p>
                                            {{-- End: Order data --}}

                                            {!! $order->shipping_observations !!}

                                            <hr>

                                            @if($order->state_id != 2 && $payment_methods->count())
                                                <p class="text-center">
                                                    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#order-modal-{{ $order->id }}">@lang('app.see-available-payment-methods')</button>
                                                </p>
                                            @endif
                                        </div>
                                        {{-- End: Panel body --}}
                                    </div>
                                    {{-- End: Collapsable area --}}
                                </div>
                                {{-- End: Panel --}}


                                {{-- Begin:: Payment methods modal --}}
                                @if($order->state_id != 2 && $payment_methods->count())
                                    <div class="modal fade" id="order-modal-{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="order-modal-label-{{ $order->id }}">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="order-modal-label-{{ $order->id }}">@lang('app.payment-methods')</h4>
                                                </div>

                                                <div class="modal-body">
                                                    @foreach($payment_methods as $method)
                                                        @include(
                                                            'front.pages.private-area.partials.payment-card',
                                                            [
                                                                'id' => $method->id,
                                                                'name' => $method->name,
                                                                'description' => $method->description,
                                                                'note' => $method->final_message,
                                                                'order' => $order
                                                            ]
                                                        )
                                                    @endforeach
                                                </div>

                                                <div class="modal-footer" style="text-align: center;">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">@lang('app.close')</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                {{-- End: Payment methods modal --}}
                            @endforeach
                        </div>
                        {{-- End: Orders list --}}
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
