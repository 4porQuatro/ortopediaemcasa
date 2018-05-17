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

{!! Form::hidden('token', $token) !!}

<div class="form-group">
    {!! Form::input('text', 'email', null, ['class' => "form-control", 'placeholder' => trans('app.email') . ' *']) !!}
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            {!! Form::input('password', 'password', null, ['class' => "form-control", 'placeholder' => trans('app.password') . ' *']) !!}
        </div>
    </div>

    <div class="col-sm-6">
        <div class="form-group">
            {!! Form::input('password', 'password_confirmation', null, ['class' => "form-control", 'placeholder' => trans('app.password-confirmation') . ' *']) !!}
        </div>
    </div>
</div>


<div class="row">
    <div class="col-xs-6">
        <span class="tip">* @lang('app.mandatory-fields')</span>
    </div>

    <div class="col-xs-6 text-right">
        <button class="btn btn-default">@lang('app.reset-password')</button>
    </div>
</div>
