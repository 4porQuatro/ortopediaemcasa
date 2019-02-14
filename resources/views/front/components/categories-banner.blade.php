@if(!empty($slides))
    <div class="banner">
        <ul class="banner__list">
            @foreach($slides as $slide)
                <li class="banner__item">
                    <a class="banner__link" href="{{ urli18n('products') . '?cat=' . $slide->id }}">{{ $slide->title }}</a>
                </li>
            @endforeach
        </ul>

        <div class="banner__slideshow">
            @foreach($slides as $slide)
                <div class="banner__placeholder">
                    <div class="banner__filter"></div>

                    <div class="banner__image" style="background: url('{{ $slide->getFirstImagePath() }}') center center no-repeat; background-size: cover;"></div>

                    <div class="banner__label">
                        <h1 class="banner__title">{{ $slide->title }}</h1>
                        <h2 class="banner__subtitle">{{ $slide->subtitle }}</h2>
                    </div>

                    <a class="banner__button" href="{{ urli18n('products') . '?cat=' . $slide->id }}">@lang('app.see-products')</a>
                </div>
            @endforeach
        </div>
    </div>
@endif
