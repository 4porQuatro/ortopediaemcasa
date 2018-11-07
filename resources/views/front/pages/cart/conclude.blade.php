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

            <div class="section__content">
                @if(!$payment_methods->count())
                    @include('front.partials.no-records-found')
                @else
                    <div class="payment-cards-list">
                        @foreach($payment_methods as $method)
                            <div class="payment-cards-list-item">
                                @include(
                                    'front.components.payment-card',
                                    [
                                        'card_modifier' => 'vertical',
                                        'id' => $method->id,
                                        'name' => $method->name,
                                        'description' => $method->description,
                                        'note' => $method->final_message,
                                        'order' => $order
                                    ]
                                )
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
