<!-- <div class="container"> -->
<div class="section">
    {{-- Begin: Newsletter Form --}}
        @include('front.components.newsletter')
    {{-- End: Newsletter Form --}}
</div>

{{-- Begin: Footer --}}

    <footer class="footer">

        {{-- Begin: Footer Top Nav --}}
        <div class="footer__top-nav">
            <h2 class="footer__top-header">{{ config('app.name') }}</h2>
            <ul class="footer__top-list">
                <li class="footer__top-item">
                    @if(auth()->check())
                        <a class="footer__top" href="{{ urli18n('user-welcome') }}">@lang('app.my-account')</a>
                    @else
                        <a class="footer__top" href="{{ urli18n('login') }}">@lang('app.login')</a>
                    @endif
                </li>
                <li class="footer__top-item">
                    <a class="footer__top-link" href="{{ urli18n('sale-conditions') }}">@lang('app.sale-conditions')</a>
                </li>
            </ul>
        </div>
        {{-- End: Footer Top Nav --}}

        {{-- Begin: Footer Bottom Nav --}}
        <div class="footer__bottom-nav">
            {{-- Begin: Footer Menu List --}}
            <ul class="footer__menu-list">
                <li class="footer__menu-item">
                    <a class="footer__menu-link" href="{{ urli18n('products') }}">@lang('app.products')</a>
                </li>
                <li class="footer__menu-item">
                    <a class="footer__menu-link" href="{{ urli18n('about') }}">@lang('app.about')</a>
                </li>
                <li class="footer__menu-item">
                    <a class="footer__menu-link" href="{{ urli18n('contacts') }}">@lang('app.contacts')</a>
                </li>
                <li class="footer__menu-item">
                    <a class="footer__menu-link" href="{{ urli18n('faqs') }}">@lang('app.faqs')</a>
                </li>
                <li class="footer__menu-item">
                    <a class="footer__menu-link" href="{{ urli18n('privacy-policy') }}">@lang('app.privacy-policy')</a>
                </li>
            </ul>
            {{-- End: Footer Menu List --}}

            {{-- Begin: Footer Partners --}}
            <div class="footer__partners">
                <div class="row">
                    @if(!empty($payment_methods))
                        <div class="col-sm-6">
                            <h4 class="footer__partners-title">@lang('app.payment-methods')</h4>

                            @foreach($payment_methods as $payment_method)
                                @php
                                    $images = $payment_method->getImages();
                                    $image = (!empty($images)) ? $images[0] : null;
                                @endphp

                                @if(!empty($image))
                                    <img class="footer__partners-image" src="{{ $payment_method->getImagesUrl() . '/' . $image->source }}" alt="{{ $image->title }}">
                                @endif
                            @endforeach
                        </div>
                    @endif

                    @if(!empty($shipping_methods))
                        <div class="col-sm-6">
                            <h4 class="footer__partners-title">@lang('app.shipping-methods')</h4>

                            @foreach($shipping_methods as $shipping_method)
                                @php
                                    $images = $shipping_method->getImages();
                                    $image = (!empty($images)) ? $images[0] : null;
                                @endphp

                                @if(!empty($image))
                                    <img class="footer__partners-image" src="{{ $shipping_method->getImagesUrl() . '/' . $image->source }}" alt="{{ $image->title }}">
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            {{-- End: Footer Partners --}}

        {{-- End: Footer Bottom Nav --}}

    </footer>
<!-- </div> -->
    {{-- Begin: Footer Credits --}}
    <div class="footer__credits">
        <p class="footer__credits-text">© 2017-{{ date('Y') }} Ortopediaemcasa.pt. @lang('app.all-rights'). @lang('app.developed-by') <a href="http://www.4por4.pt ">4por4</a></p>
    </div>
    {{-- End: Footer Credits --}}
</div>
{{-- End: Footer --}}
