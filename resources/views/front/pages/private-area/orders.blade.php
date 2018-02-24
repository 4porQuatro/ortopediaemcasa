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
                <!-- Begin: User menu -->
                <div class="col-sm-3">
                    @include('front.pages.private-area.partials.user-menu')
                </div>
                <!-- End: User menu -->

                <div class="col-sm-9">
                    <!-- Begin: Page article -->
                    @if($article = $page->articles->shift())
                        <div class="editable">
                            <h3>{{ $article->title }}</h3>

                            {!! $article->content !!}
                        </div>
                    @endif
                    <!-- End: Page article -->

                    @if(!$user->orders->count())
                        <div class="alert alert-info">
                            @lang('app.no-orders-placed')
                        </div>
                    @else
                        <!-- Begin: Orders list -->
                        <ul class="accordion">
                            <!-- Begin: Order -->
                            @foreach($user->orders as $key => $order)
                                <li>
                                    <div class="accordion__header">
                                        <div class="row">
                                            <div class="col-xs-8">
                                                @lang('app.order') #{{ $order->id }} |
                                                <span>{{ $order->created_at->formatLocalized('%d %B %Y') }}</span> |
                                                <span>{{ $order->state->title }}</span>
                                            </div>
                                            <div class="col-xs-2 text-right">
                                                <span class="{{ $order->state->id == 2 ? ' color-info' : ' color-danger' }}">{{ $order->points_earned }} {{ $order->points_earned == 1 ? 'ponto' : 'pontos' }}{{ $order->state->id == 2 ? '' : ' *' }}</span>
                                            </div>
                                            <div class="col-xs-2">
                                                <button
                                                        type="button"
                                                        class="accordion__btn link-btn pull-right"
                                                        data-toggle="collapse"
                                                        data-target="#collapse{{ $key }}" aria-expanded="false"
                                                        aria-controls="collapseExample"
                                                >
                                                    <span class="icon icon--arrow-down"></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="accordion__text collapse" id="collapse{{ $key }}">
                                        <!-- Begin: Items -->
                                        <table class="cart-table table">
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
                                                        $attributes = json_decode($item->attributes);
                                                    @endphp
                                                    <tr>
                                                        <td class="min-width-cell hidden-xs">
                                                            <div class="cart-table__image pull-left"
                                                                 style="background-image:url({{ $item->image_url }})"></div>
                                                        </td>
                                                        <td>
                                                            <p>
                                                                <i>{{ $attributes->category->name }}</i><br>
                                                                {{ $item->name }}
                                                            </p>
                                                            <p>
                                                                <b>@lang('app.color'):</b> {{ $attributes->color->name }}<br>
                                                                <b>@lang('app.size'):</b> {{ $attributes->size->name }}
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
                                                        @lang('app.voucher-discount'):<br>
                                                        @lang('app.points-discount'):<br>
                                                        <b>Total:</b>
                                                    </td>
                                                    <td class="text-right" style="width:1%; white-space:nowrap;">
                                                        {{ $order->items_total }} €<br>
                                                        {{ $order->shipping_cost }} €<br>
                                                        {{ ($order->voucher_discount > 0) ? '-' : '' }}{{ $order->voucher_discount }} €<br>
                                                        {{ ($order->points_discount > 0) ? '-' : '' }}{{ $order->points_discount }} €<br>
                                                        <b>{{ $order->total }} €</b>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                        <!-- End: Items -->

                                        <hr>

                                        <div class="row">
                                            <div class="col-xs-6">
                                                <p>
                                                    <b>Método de envio:</b> {{ $order->shipping_method }}<br>
                                                </p>
                                            </div>

                                            @if($order->state_id != 2 && $payment_methods->count())
                                                <div class="col-xs-6 text-right">
                                                    <p>
                                                        <button type="button" class="btn-square btn-square-dark" data-toggle="modal" data-target="#order-modal-{{ $order->id }}">@lang('app.see-payment-methods')</button>
                                                    </p>
                                                </div>
                                            @endif
                                        </div>

                                        {!! $order->shipping_observations !!}

                                        <!-- Begin:: Payment methods -->
                                        @if($order->state_id != 2 && $payment_methods->count())
                                            <!-- Begin: Modal -->
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
                                                                    'components.payment-card',
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
                                                            <button type="button" class="btn-square btn-square-default" data-dismiss="modal">@lang('app.close')</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End: Modal -->
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                            <!-- End: Order -->
                        </ul>
                        <!-- End: Orders list -->
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
