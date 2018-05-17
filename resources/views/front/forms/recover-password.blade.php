@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif

<div class="form-group">
    {!! Form::input('text', 'email', null, ['class' => "form-control", 'placeholder' => 'E-mail *']) !!}
</div>


<div class="row">
    <div class="col-xs-6">
        <span class="tip">* @lang('app.mandatory-fields')</span>
    </div>

    <div class="col-xs-6 text-right">
        <button class="btn btn-default" type="submit">@lang('app.recover-password')</button>
    </div>
</div>
