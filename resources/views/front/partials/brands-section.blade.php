<div class="section">
    @if(!empty($article))
        <div class="section__container">
            <h1 class="section__title">{{ $article->title }}</h1>
        </div>
    @endif

    <div class="partners">
        <div class="partners__slideshow">
            @for($i = 0; $i < 10; $i++)
                <!-- Begin: Partners Banner -->
                @include('front.components.partner-image', [
                    'image' => '/front/images/logo/partners/AMD.jpg'
                ])
                <!-- End: Partners Banner -->
            @endfor
        </div>
    </div>
</div>