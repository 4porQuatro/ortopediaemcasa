@if(count($errors->user_pass) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->user_pass->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session()->has('status'))
    <div class="alert alert-success">
        {{ session()->get('status') }}
    </div>
@endif

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            {!! Form::input('password', 'old_password', null, ['class' => "form-control", 'placeholder' => trans('app.old-password') . ' *']) !!}
        </div>
    </div>
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

<div class="text-right">
    <button type="submit" class="btn btn-default">@lang('app.save')</button>
</div>
