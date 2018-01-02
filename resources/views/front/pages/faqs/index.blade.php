@extends('/front/layouts/app')

@section('content')
    @include('front.components.breadcrumbs', [
            
    ])
    <div class="container">
        <div class="section first">
            <h2 class="subsection__title">Perquntas Frequentes</h2>
            <h2 class="subsection__subtitle">perguntas e respostas para as suas compras online</h2>
            <div class="faqs__wrapper">
                @foreach($questions as $question)
                    @include('front.components.faqs', [
                        'question' => $question->question,
                        'answer' => $question->answer
                    ])
                @endforeach
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