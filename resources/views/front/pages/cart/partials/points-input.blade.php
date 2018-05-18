@if(auth()->check())
    <div class="cart-form">
        <div class="cart-form-table">
            <div class="form-group">
                <label for="points">@lang('app.points-to-discount')</label>
                {!! Form::input('number', 'points', $points_spent, ['id' => "points-input", 'class' => 'form-control', 'placeholder' => "Insira os pontos a descontar", 'min' => "0", 'max' => auth()->user()->getAvailablePoints()]) !!}
            </div>

            <div class="cart-form-btns">
                {!! Form::button('', ['class' => "btn-circle btn-circle-default zmdi zmdi-plus", 'id' => "add-points-btn"]) !!}
                {!! Form::button('', ['class' => "btn-circle btn-circle-default zmdi zmdi-minus", 'id' => "remove-points-btn"]) !!}
            </div>
        </div>

        <p><i><b>@lang('app.available-points'):</b> {{ auth()->user()->getAvailablePoints() }}</i></p>

        <div class="cart-form-results-displayer" id="points-results-displayer"></div>
    </div>
@endif