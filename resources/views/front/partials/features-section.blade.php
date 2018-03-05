@if(!empty($article))
    <div class="section">
        <div class="features">
            <h2 class="features__title">{{ $article->title }}</h2>
            <h2 class="features__subtitle">{{ $article->subtitle }}</h2>

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
@endif