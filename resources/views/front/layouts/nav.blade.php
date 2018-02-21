<!-- Begin: Navigation-Bar -->
<nav class="navbar">
    <div class="container">

        <!-- Begin: Navbar Logo -->
        <a class="navbar__logo" href="{{ urli18n() }}">
            <img src="/front/images/logo/logo.png" alt="logo">
        </a>
        <!-- End: Navbar Logo -->

        <!-- Begin: Search Tool -->
        <form class="navbar__search">
            <input class="search-input" placeholder="inserir termo...">
            <button class="search-button">@lang('app.search')</button>
        </form>
        <!-- End: Search Tool -->


            <!-- Begin: Navbar Store -->
            <div class="navbar__store">
                <a class="navbar__account" href="/"><i class="zmdi zmdi-account"></i>@lang('app.my-account')</a>
                <a class="navbar__shopping-bag" href="/"><i class="zmdi zmdi-shopping-cart"></i>[ 0 ]</a>
            </div>
            <!-- End: Navbar Store -->

        <div class="collapse navbar-collapse" id="myNavbar">
            <!-- Begin: Navbar Menu -->
            <div class="navbar__menu">
                <ul class="navbar__list">
                    <li class="navbar__item">
                        <a class="navbar__link" href="{{ urli18n("products") }}">@lang('app.products')</a>
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
            <!-- End: Navbar Menu -->
        </div>

        <!-- Begin: Button Responsive Menu -->
            <button type="button" id="menu-button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        <!-- End: Button Responsive Menu -->
    </div>
</nav>
<!-- End: Navigation-Bar -->
