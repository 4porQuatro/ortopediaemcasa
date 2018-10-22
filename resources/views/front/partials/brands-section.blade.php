<div class="section">
    @if(!empty($article))
        <div class="section__container">
            <h1 class="section__title">{{ $article->title }}</h1>
        </div>
    @endif

    @if(!empty($brands))
        <div class="partners">
            <div class="partners__slideshow">

                @foreach($brands as $brand)

                    @include(
                        'front.components.partner-image',
                        [
                            'image' => $brand->getFirstImagePath()
                        ]
                    )

                @endforeach
            </div>
        </div>
    @endif
</div>