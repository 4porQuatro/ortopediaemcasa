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
                @foreach($contacts_info as $contact_info)     
                    @include('front.components.contact-icon', [
                        'icon' => '',
                        'title' => $contact_info->title,
                        'text' => $contact_info->text
                    ])
                @endforeach
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
