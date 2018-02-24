@if(count($errors->user) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->user->all() as $error)
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

<h4>@lang('app.billing-info')</h4>
<br>

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
        {!! Form::input('text', 'vat_number', old('vat_number'), ['class' => "form-control", 'placeholder' => trans('app.vat-number') . ' *']) !!}
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

<hr>

<h4>@lang('app.shipping-info')</h4>
<br>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            {!! Form::input('text', 'shipping_name', null, ['class' => "form-control", 'placeholder' => trans('app.name') . ' *']) !!}
        </div>
    </div>

    <div class="col-sm-6">
        <div class="form-group">
            {!! Form::input('text', 'shipping_phone', null, ['class' => "form-control", 'placeholder' => trans('app.phone') . ' *']) !!}
        </div>
    </div>

    <div class="col-sm-6">
        <div class="form-group">
            {!! Form::input('text', 'shipping_address', null, ['class' => "form-control", 'placeholder' => trans('app.address') . ' *']) !!}
        </div>
    </div>

    <div class="col-sm-6">
        <div class="form-group">
            {!! Form::input('text', 'shipping_city', null, ['class' => "form-control", 'placeholder' => trans('app.city') . ' *']) !!}
        </div>
    </div>

    <div class="col-sm-6">
        <div class="form-group">
            {!! Form::input('text', 'shipping_zip_code', null, ['class' => "form-control", 'placeholder' => trans('app.zip-code') . ' *']) !!}
        </div>
    </div>

    <div class="col-sm-6">
        <div class="form-group">
            {!! Form::select('shipping_country_id', $countries, null, ['class' => "form-control", 'placeholder' => trans('app.country') . ' *']) !!}
        </div>
    </div>
</div>


<div class="row">
    <div class="col-xs-6">
        <span>* @lang('app.mandatory-fields')</span>
    </div>

    <div class="col-xs-6 text-right">
        <button type="submit" class="btn btn-default">{{ $btn_name }}</span>
        </button>
    </div>
</div>
