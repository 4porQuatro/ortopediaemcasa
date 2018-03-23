<div class="from-group row">
    @if(!empty($product->itemCategory->itemAttributeTypes))
        @foreach($product->itemCategory->itemAttributeTypes as $attribute_type)
            <div class="col-xs-12 col-md-6">
                <label class="product__label" for="item_attr_{{ $attribute_type->id }}">{{ $attribute_type->title }}</label>
                <select class="input-form product__select" name="item_attr_{{ $attribute_type->id }}" id="item_attr_{{ $attribute_type->id }}">
                    @foreach($attribute_type->itemAttributeValues as $attribute_value)
                        <option value="{{ $attribute_value->id }}">{{ $attribute_value->title }}</option>
                    @endforeach
                </select>
            </div>
        @endforeach
    @endif

    <div class="col-xs-12 col-md-6">
        <label class="product__label" name="size">Quantidade</label>
        <input class="product__input" type="number" placeholder="1">   
    </div>
</div>