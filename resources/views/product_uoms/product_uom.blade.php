
    <div class='form-group  @if ($errors->has('uom_rate')) has-error @endif'>
        <label for='uom_rate' class='col-sm-2 control-label'>Rate<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('uom_rate', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('uom_rate')) <p class="help-block">{{ $errors->first('uom_rate') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('uom_cost')) has-error @endif'>
        {{ Form::label('uom_cost', 'Cost',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('uom_cost', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('uom_cost')) <p class="help-block">{{ $errors->first('uom_cost') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('uom_price')) has-error @endif'>
        {{ Form::label('uom_price', 'Price',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('uom_price', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('uom_price')) <p class="help-block">{{ $errors->first('uom_price') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/product/uom/{{ $product->product_code }}" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>

	{{ Form::hidden('product_code', $product->product_code) }}

