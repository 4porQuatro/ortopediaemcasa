<div class="section">
    <div class="features">
        @if(!empty($article))
            <h2 class="features__title">{{ $article->title }}</h2>
            <h2 class="features__subtitle">{{ $article->subtitle }}</h2>
        @endif

        @if(!empty($features))
            <div class="row">
                @foreach($features as $feature)
                <!-- Begin: Features Icon -->
                    <div class="col-xs-12 col-md-4">
                        @include('front.components.feature-icon', [
                            'icon' => $feature->getFirstImagePath(),
                            'name' => $feature->title
                        ])
                    </div>
                    <!-- End: Features Icon -->
                @endforeach
            </div>
        @endif
    </div>
</div>