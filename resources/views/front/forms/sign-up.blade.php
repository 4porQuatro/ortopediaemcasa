@if(count($errors->user) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->user->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            {!! Form::input('text', 'billing_name', null, ['class' => "form-control", 'placeholder' => trans('app.name') . ' *']) !!}
        </div>
    </div>

    <div class="col-sm-6">
        <div class="form-group">
            {!! Form::input('text', 'email', null, ['class' => "form-control", 'placeholder' => trans('app.email') . ' *']) !!}
        </div>
    </div>

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

    <div class="col-sm-6">
        <div class="form-group">
            {!! Form::input('text', 'vat_number', null, ['class' => "form-control", 'placeholder' => trans('app.vat-number') . ' *']) !!}
        </div>
    </div>

    <div class="col-sm-6">
        <div class="form-group">
            {!! Form::input('text', 'billing_phone', null, ['class' => "form-control", 'placeholder' => trans('app.phone') . ' *']) !!}
        </div>
    </div>

    <div class="col-sm-6">
        <div class="form-group">
            {!! Form::input('text', 'billing_address', null, ['class' => "form-control", 'placeholder' => trans('app.address') . ' *']) !!}
        </div>
    </div>

    <div class="col-sm-6">
        <div class="form-group">
            {!! Form::input('text', 'billing_city', null, ['class' => "form-control", 'placeholder' => trans('app.city') . ' *']) !!}
        </div>
    </div>

    <div class="col-sm-6">
        <div class="form-group">
            {!! Form::input('text', 'billing_zip_code', null, ['class' => "form-control", 'placeholder' => trans('app.zip-code') . ' *']) !!}
        </div>
    </div>

    <div class="col-sm-6">
        <div class="form-group">
            {!! Form::select('billing_country_id', $countries, null, ['class' => "form-control", 'placeholder' => trans('app.country') . ' *']) !!}
        </div>
    </div>
</div>


<div class="row">
    <div class="col-xs-6">
        <span>* @lang('app.mandatory-fields')</span>
    </div>

    <div class="col-xs-6 text-right">
        <button type="submit" class="btn btn-default"><span class="zmdi zmdi-account"></span> {{ $btn_name }}</button>
    </div>
</div>
