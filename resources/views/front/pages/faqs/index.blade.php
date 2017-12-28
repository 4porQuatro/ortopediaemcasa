@extends('/front/layouts/app')

@section('content')
    <div class="container">
        <div class="section first">
            <h2 class="subsection__title">Perquntas Frequentes</h2>
            <h2 class="subsection__subtitle">perguntas e respostas para as suas compras online</h2>
            <div class="faqs__wrapper">
            @include('front.components.faqs', [

            ])

            @include('front.components.faqs', [
                
            ])

            @include('front.components.faqs', [
                
            ])
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