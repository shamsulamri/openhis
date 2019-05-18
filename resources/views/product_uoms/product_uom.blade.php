
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
        {{ Form::label('uom_price', 'Price 1',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('uom_price', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('uom_price')) <p class="help-block">{{ $errors->first('uom_price') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('uom_price_2')) has-error @endif'>
        {{ Form::label('uom_price_2', 'Price 2',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('uom_price_2', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('uom_price_2')) <p class="help-block">{{ $errors->first('uom_price_2') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('uom_default_cost')) has-error @endif'>
        <label for='uom_default_cost' class='col-sm-2 control-label'>Default Cost</label>
        <div class='col-sm-10'>
			{{ Form::checkbox('uom_default_cost', '1') }} 
        </div>
    </div>

    <div class='form-group  @if ($errors->has('uom_default_price')) has-error @endif'>
        <label for='uom_default_price' class='col-sm-2 control-label'>Default Price</label>
        <div class='col-sm-10'>
			{{ Form::checkbox('uom_default_price', '1') }} 
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/product/uom/{{ $product->product_code }}" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>

	<br>
	<h5>Note</h5>
	Price 1: Outpatient/Public price<br>
	Price 2: Inpatient/Sponsor price
	{{ Form::hidden('product_code', $product->product_code) }}

