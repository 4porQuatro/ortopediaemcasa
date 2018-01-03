
<div class="banner">
    <ul class="banner__list">
        @foreach($categories as $menu_item)
        <li class="banner__item">
            <a class="banner__link" href="#">{{$menu_item->title}}</a>
        </li>
        @endforeach
    </ul>
    <select class="category-select" name="category" id="categories">
        @foreach($categories as $menu_item)
        <option value="">{{$menu_item->title}}</option>
        @endforeach
    </select>
    <div class="banner__slideshow">
        @foreach($categories as $slide)
        <div class="banner__placeholder">
            <div class="banner__filter"></div>
            <div class="banner__image" style="background: url('{{$slide->image}}') center center no-repeat; background-size: cover;"></div>
            <div class="banner__label">
                <h1 class="banner__title">{{$slide->title}}</h1>
                <h2 class="banner__subtitle">{{$slide->subtitle}}</h2>
            </div>
            <a class="banner__button" href="{{$slide->link_path}}">ver produtos</a>
        </div>
        @endforeach
    </div>
</div>