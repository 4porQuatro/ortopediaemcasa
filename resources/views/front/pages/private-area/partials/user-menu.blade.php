<div>
    @if(!empty($user_menus))
        @foreach($user_menus as $menu)
            @php
                $active_class = (Request::path() == $menu['href']) ? ' filter-card__title--active' : '';
            @endphp
            <div class="filter-card__title{{ $active_class }}">
                <a href="{{ urli18n($menu['href']) }}">{{ $menu['title'] }}</a>
            </div>
        @endforeach
    @endif

    <!-- Begin: Logout form -->
    <div>
        {!! Form::open(['action' => 'Auth\LoginController@logout']) !!}
            <button type="submit" class="">@lang('app.logout')</button>
        {!! Form::close() !!}
    </div>
    <!-- End: Logout form -->
</div>

<!-- Begin: Points displayer -->
<div>
    <h3>@lang('app.my-points')</h3>
    <h4>{{ auth()->user()->getAvailablePoints() }}</h4>
</div>
<!-- End: Points displayer -->
