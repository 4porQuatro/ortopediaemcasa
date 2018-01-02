@extends('/front/layouts/app')

@section('content')
@include('front.components.breadcrumbs', [
            
])
<div class="container">
    <div class="section first">
        <h2 class="subsection__title text-center">Formulário de Contacto</h2>
        <h2 class="subsection__subtitle text-center">Respondemos a todas as suas questões</h2>
        @include('front.forms.contact-form', [])
        <div class="contact-icons">
            <div class="row">

                    @include('front.components.contact-icon', [
        
                    ])
      
            </div>
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
