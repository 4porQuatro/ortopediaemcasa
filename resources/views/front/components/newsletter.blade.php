<div class="newsletter">
    <div class="newsletter__title-container">
        <h2 class="newsletter__title">Junte-se a Nós...</h2>
        <h2 class="newsletter__subtitle">e ganhe 10% de desconto</h2>
    </div>

    {!! Form::open(['action' => 'Newsletter\SubscriptionController@store', 'class' => 'newsletter__form', 'autocomplete' => 'off']) !!}
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

        <h4 class="newsletter__form-title">Seja o primeiro a receber as melhores ofertas</h4>
        <input name="email" class="newsletter__input" placeholder="Inserir Email">
        <button class="newsletter__button" type="submit">submeter</button>
    {!! Form::close() !!}
</div>
