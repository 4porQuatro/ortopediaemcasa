@extends('/front/layouts/app')

@section('content')
    <div class="container">
        <div class="section first">
            <h2 class="subsection__title">Perquntas Frequentes</h2>
            <h2 class="subsection__subtitle">perguntas e respostas para as suas compras online</h2>
            <div class="subsection__description editable">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
            </div>
        </div>
        <div class="section">
            <!-- Begin: Newsletter Form -->
            @include('front.components.newsletter', [

            ])
            <!-- End: Newsletter Form -->
        </div>
    </div>
@endsection