@if(!empty($user_menus))
    <ul class="list-group">
        @foreach($user_menus as $menu)
            @php
                $active_class = (Request::path() == $menu['href']) ? ' active' : '';
            @endphp
            <li class="list-group-item{{ $active_class }}">
                <a href="{{ urli18n($menu['href']) }}">{{ $menu['title'] }}</a>
            </li>
        @endforeach
        <li class="list-group-item">
            {{-- Begin: Logout form --}}
            {!! Form::open(['action' => 'Auth\LoginController@logout']) !!}
            <button type="submit" class="btn-link no-padding-left no-padding-right">@lang('app.logout')</button>
            {!! Form::close() !!}
            {{-- End: Logout form --}}
        </li>
    </ul>
@endif

{{-- Begin: Points displayer --}}
{{--<div>--}}
    {{--<h3>@lang('app.my-points')</h3>--}}
    {{--<h4>{{ auth()->user()->getAvailablePoints() }}</h4>--}}
{{--</div>--}}
{{-- End: Points displayer --}}
