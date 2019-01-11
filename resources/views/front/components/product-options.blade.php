<div class="from-group row">
    @if(!empty($product->itemCategory->itemAttributeTypes))
        @foreach($product->itemCategory->itemAttributeTypes as $attribute_type)
            @php
                $item_attr_type_vals = $product->itemAttributeValues->where('item_attribute_type_id', $attribute_type->id);
            @endphp
            @if($item_attr_type_vals->count())
                <div class="col-xs-12 col-md-6">
                    <div class="form-group">
                        <label class="product__label" for="item_attr_{{ $attribute_type->id }}">{{ $attribute_type->title }}</label>
                        <select class="input-form product__select" name="item_attr[{{ $attribute_type->id }}]" id="item_attr_{{ $attribute_type->id }}">
                            @foreach($item_attr_type_vals as $attribute_value)
                                <option value="{{ $attribute_value->id }}">{{ $attribute_value->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif
        @endforeach
    @endif

    <div class="col-xs-12 col-md-6">
        <div class="form-group">
            {!! Form::label('quantity', trans('app.quantity'), ['class' => "product__label"]) !!}

            <div class="minusplusnumber">
               <div class="mpbtn minus"></div>
                  <div id="field_container">
                     {!! Form::input('number', 'quantity', null, ['class' => "product__input", 'min' => 1]) !!}
                  </div>
               <div class="mpbtn plus"></div>
            </div>

        </div>
    </div>
</div>

{!! Form::hidden('item_id', $product->id) !!}
