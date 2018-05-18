<div class="cart-form">
    <div class="cart-form-table">
        <div class="form-group">
            <label for="voucher">@lang('app.voucher')</label>
            {!! Form::text('voucher', $voucher_code, ['id' => "voucher", 'class' => 'form-control', 'placeholder' => trans('app.insert-promo-code')]) !!}
        </div>

        <div class="cart-form-btns">
            {!! Form::button('', ['class' => "btn-circle btn-circle-default zmdi zmdi-plus", 'id' => "add-voucher-btn"]) !!}
            {!! Form::button('', ['class' => "btn-circle btn-circle-default zmdi zmdi-minus", 'id' => "remove-voucher-btn"]) !!}
        </div>
    </div>

    <div class="cart-form-results-displayer" id="voucher-results-displayer"></div>
</div>