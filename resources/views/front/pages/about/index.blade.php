@extends('/front/layouts/app')

@section('content')
    <div class="container">
        <div class="section first">
            @include('front.components.about-banner', [

            ])
            <div class="about__description editable">
                <h2>Sobre a empresa</h2>
                <p>A “Ortopedia em Casa®” surge com a missão de disponibilizar aos seus clientes as melhores soluções para cada caso em concreto, a um preço competitivo. Sendo um projeto novo, está alicerçado num grupo de empresas ligadas ao setor do comércio por grosso de artigos médicos, ortopédicos, de saúde e bem estar. Este facto permite-nos ter uma vasta experiência na seleção das melhores marcas e das melhores opções de mercado em cada artigo, privilegiando sempre e em primeiro lugar, a qualidade, e em segundo lugar, a competitividade em termos de preço.</p>
            </div>
        </div>
        <div class="section">
            <div class="features">
                <h2 class="features__title">Porquê Ortopedia em Casa</h2>
                <h2 class="features__subtitle">artigos médicos, ortopédicos, de saúde e bem estar</h2>
                <div class="row">
                    @foreach($features as $key => $feature)
                        <!-- Begin: Features Icon -->
                        <div class="col-xs-12 col-md-4">
                            @include('front.components.feature-icon', [
                                'icon' => $feature->icon,
                                'name' => $feature->name
                            ])
                        </div>
                        <!-- End: Features Icon -->
                    @endforeach
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section__container">
                <h2 class="section__title">Oferecemos uma vasta gama de marcas...</h2>
            </div>
            <div class="partners">
                <div class="partners__slideshow">
                    @foreach($partners as $key => $partner)
                    <!-- Begin: Partners Banner -->
                        @include('front.components.partner-image', [
                            'image' => $partner->image
                        ])
                    <!-- End: Partners Banner -->
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