<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            {!! Form::input('text', 'email', null, ['class' => "form-control", 'placeholder' => trans('app.email') . ' *']) !!}
        </div>

        @if(count($errors->user) == 0 && $errors->has('email'))
            <span class="help-block">
                {{ $errors->first('email') }}
            </span>
        @endif
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            {!! Form::input('password', 'password', null, ['class' => "form-control", 'placeholder' => trans('app.password') . ' *']) !!}
        </div>

        @if(count($errors->user) == 0 && $errors->has('password'))
            <span class="help-block">
                {{ $errors->first('password') }}
            </span>
        @endif
    </div>
</div>

<div class="form__footer">
    <div class="row">
        <div class="col-xs-6">
            <a href="{{ urli18n('password-reset') }}" class="btn btn-link">@lang('app.recover-password')</a>
        </div>
        <div class="col-xs-6 text-right">
            <button type="submit" class="btn btn-default" onclick="location.href='user'"><span class="zmdi zmdi-account"></span> @lang('app.login')</button>
        </div>
    </div>
</div>
