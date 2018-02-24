{!! Form::open(['method' => 'GET', 'url' => urli18n('search'), 'class' => 'search__form']) !!}
    {!! Form::text('search', null, ['placeholder' => trans('app.search')]) !!}
    {!! Form::button('', ['type' => 'submit', 'class' => 'icon icon--search icon-btn']) !!}
{!! Form::close() !!}
