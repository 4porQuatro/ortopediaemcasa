@if(empty($shipping_methods))
<div class="alert alert-warning text-center">
   @lang('app.login-for-shipping-methods')
</div>
@else
<div class="row">
   @foreach($shipping_methods as $method)
   <div class="col-xs-8">
      <p><b>{{ $method->name }}</b> {!! (!empty($method->description)) ? '<i>(' . $method->description . ')</i>' : '' !!}</p>            </div>
      <div class="col-xs-4 text-right">               
          {{ $method->formattedPrice($items_weight, $user->shippingCountry->id) }}
          {{ Form::radio('shipping_method_id', $method->id, ($method->id == $selected_shipping_method_id)) }}
   </div>
   @endforeach
</div>
@endif
