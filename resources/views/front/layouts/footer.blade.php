<!-- Begin: Footer -->
<div class="container">
    <footer class="footer">

        <!-- Begin: Footer Top Nav -->
        <div class="footer__top-nav">
            <h2 class="footer__top-header">{{ config('app.name') }}</h2>
            <ul class="footer__top-list">
                <li class="footer__top-item">
                    <a class="footer__top-link" href="/signin">@lang('app.create-account')</a>
                </li>
                <li class="footer__top-item">
                    <a class="footer__top-link" href="/account">@lang('app.my-account')</a>
                </li>
                <li class="footer__top-item">
                    <a class="footer__top-link" href="{{ urli18n('sale-conditions') }}">@lang('app.sale-conditions')</a>
                </li>
            </ul>
        </div>
        <!-- End: Footer Top Nav -->

        <!-- Begin: Footer Bottom Nav -->
        <div class="footer__bottom-nav">
            <!-- Begin: Footer Menu List -->
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
            <!-- End: Footer Menu List -->

            <!-- Begin: Footer Partners -->
            <div class="footer__partners">
                <div class="footer__partners-upper">
                    <h4 class="footer__partners-title">Método de Pagamento:</h4>
                    <img class="footer__partners-image" src="/front/images/logo/partners/AMD.jpg" alt="partner">
                    <img class="footer__partners-image" src="/front/images/logo/partners/AMD.jpg" alt="partner">
                </div>
                <div class="footer__partners-lower">
                    <h4 class="footer__partners-title">Ambiente Seguro:</h4>
                    <img class="footer__partners-image" src="/front/images/logo/partners/AMD.jpg" alt="partner">
                </div>
                <div class="footer__partners-lower">
                    <h4 class="footer__partners-title">Formas de Entrega:</h4>
                    <img class="footer__partners-image" src="/front/images/logo/partners/AMD.jpg" alt="partner">
                </div>

            </div>
            <!-- End: Footer Partners -->

        </div>
        <!-- End: Footer Bottom Nav -->

    </footer>
    <!-- Begin: Footer Credits -->
    <div class="footer__credits">
        <p class="footer__credits-text">© 2017-{{ date('Y') }} Ortopediaemcasa.pt. @lang('app.all-rights'). @lang('app.developed-by') <a href="http://www.4por4.pt ">4por4</a></p>
    </div>
    <!-- End: Footer Credits -->
</div>
<!-- End: Footer -->
