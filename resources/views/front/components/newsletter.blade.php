<div class="newsletter">
    <div class="newsletter__title-container">
        @if(!empty($article))
            <h2 class="newsletter__title">{{ $article->title }}</h2>
            <h2 class="newsletter__subtitle">{{ $article->subtitle }}</h2>
        @endif
    </div>

    {!! Form::open(['action' => 'Newsletter\SubscriptionController@store', 'class' => 'newsletter__form', 'autocomplete' => 'off', 'data-async' => "true"]) !!}
        @if (count($errors->newsletter) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->newsletter->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(Session::has('newsletter_msg'))
            <div class="alert alert-success">
                {{ Session::get('newsletter_msg') }}
            </div>
        @endif

        <div class="result-displayer"></div>

        @if(!empty($article))
            <p class="newsletter__form-title"><b>{{ strip_tags($article->content) }}</b></p>
        @endif
        <input name="email" class="newsletter__input" placeholder="@lang('app.type-your-email')">
        <button class="newsletter__button" type="submit">@lang('app.submit')</button>
    {!! Form::close() !!}
</div>
