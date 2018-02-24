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

<div class="form-wrapper">
    {!! Form::input('text', 'email', null, ['placeholder' => 'Insere o teu email']) !!}
    {!! Form::button('', ['type' => 'submit', 'class' => 'form__btn btn-icon icon icon--send']) !!}
</div>
