<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @yield('meta')
        <link rel="stylesheet" href="/front/css/libs/lg-transitions.css" media="screen">
        <link rel="stylesheet" href="/front/css/libs/lightgallery.css" media="screen">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css">
        <link rel="stylesheet" href="{{ mix('/front/css/app.css') }}" media="screen">
        <link rel="stylesheet" href="{{ asset('/front/css/bootstrap-bigger-container.css') }}">
        @stack('css')
    </head>

    <body>
        <div id="app">
            <!-- Begin: Header -->
            @include('front.layouts.nav')
            <!-- End: Header -->

            <!-- Begin: Content -->
            @yield('content')
            <!-- End: Content -->

            <!-- Begin: Footer-->
            @include('front.layouts.footer')
            <!-- End: Footer-->
        </div>

        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBGdi7QwIqsN16C3N0J74gtwzGvM4MCJkc"></script>
        <script src="/front/js/map.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.2.0/aos.js"></script>
        <script src="{{ asset('front/js/async-forms.js') }}"></script>
        <script src="{{ mix('/front/js/app.js') }}"></script>
        <script>
            $(document).ready(function(){
                asyncForms();
            });
        </script>
        @stack('scripts')
    </body>
</html>
