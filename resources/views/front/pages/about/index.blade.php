@extends('/front/layouts/app')

@section('content')
    <div class="container">
        <div class="section first">
            @include('front.components.about-banner', [
                'image' => $about_banner->image,
                'title' => $about_banner->title,
                'subtitle' => $about_banner->subtitle
            ])
            <div class="about__description editable">
                <h2>Sobre a empresa</h2>
                {!! $about_description->text !!}
            </div>
        </div>
        <div class="section">
            <div class="features">
                <h2 class="features__title">{{$section->title}}</h2>
                <h2 class="features__subtitle">{{$section->subtitle}}</h2>
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