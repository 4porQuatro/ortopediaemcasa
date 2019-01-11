{{-- Begin: Navigation-Bar --}}
<nav class="navbar">
    <div class="container">

        {{-- Begin: Navbar Logo --}}
        <a class="navbar__logo" href="{{ urli18n() }}">
            <img src="/front/images/logo/logo.png" alt="logo">
        </a>
        {{-- End: Navbar Logo --}}

        {{-- Begin: Search Tool --}}
        <form action="{{ action('ProductsController@index') }}" method="GET" class="navbar__search">
            {!! Form::text('search', null, ['placeholder' => trans('app.type-your-search'), 'class' => "search-input"]) !!}
            <button class="search-button">@lang('app.search')</button>
        </form>
        {{-- End: Search Tool --}}

        {{-- Begin: Navbar Store --}}
        <div class="navbar__store">
            @if(auth()->check())
                <a class="navbar__account" href="{{ urli18n('user-welcome') }}"><i class="zmdi zmdi-account"></i> @lang('app.my-account')</a>
            @else
                <a class="navbar__account" href="{{ urli18n('login') }}"><i class="zmdi zmdi-account"></i> @lang('app.login')</a>
            @endif

            <a class="navbar__shopping-bag" href="{{ urli18n('checkout') }}"><i class="zmdi zmdi-shopping-cart"></i>[ {{ Cart::instance('items')->count() }} ]</a>
        </div>
        {{-- End: Navbar Store --}}

        <div class="navbar__wrap collapse navbar-collapse" id="menu">
            {{-- Begin: Navbar Menu --}}
            <div class="navbar__menu">
                <ul class="navbar__list">
                    <li class="navbar__item dropdown">
                        <a class="navbar__link desk" href="{{ urli18n("products") }}">@lang('app.products')</a>
                        <a class="navbar__link mobile dropdown-toggle" aria-expanded="true" aria-controls="submenu" data-toggle="collapse" >@lang('app.products')</a>
                        <!-- products list -->
                        <div id="submenu" class="navbar__submenu multi-collapse collapse">
                        @include(
                           'front.partials.multiLevelMenu',
                           [
                               'html' => $menu_html,
                               'class' => 'menu',
                           ]
                        )
                       </div>
                       <!-- products list -->
                    </li>
                    <li class="navbar__item">
                        <a class="navbar__link" href="{{ urli18n("about") }}">@lang('app.about')</a>
                    </li>
                    <li class="navbar__item">
                        <a class="navbar__link" href="{{ urli18n("faqs") }}">@lang('app.faqs')</a>
                    </li>
                    <li class="navbar__item">
                        <a class="navbar__link" href="{{ urli18n("contacts") }}">@lang('app.contacts')</a>
                    </li>
                    <li class="navbar__item--responsive">
                        <a class="navbar__link" href="#"><i class="zmdi zmdi-account"></i>@lang('app.my-account')</a>
                    </li>
                    <li class="navbar__item--responsive">
                        <a class="navbar__link" href="#"><i class="zmdi zmdi-shopping-cart"></i>[ 0 ]</a>
                    </li>
                </ul>
            </div>
            {{-- End: Navbar Menu --}}
        </div>

        {{-- Begin: Button Responsive Menu --}}
            <button type="button" id="menu-button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#menu">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        {{-- End: Button Responsive Menu --}}
    </div>
</nav>
{{-- End: Navigation-Bar --}}

@push('scripts')

<script>

     var submenu = $(".navbar__submenu");

    $('.dropdown-toggle').click(function () {
        submenu.toggleClass("show");
    });
    $('.navbar__submenu li').click(function () {
        // closes all others
        $(this).siblings('li').children('ul').css('display', 'none');
    });

</script>

@endpush
