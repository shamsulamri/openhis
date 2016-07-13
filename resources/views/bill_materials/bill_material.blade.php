
    <div class='form-group  @if ($errors->has('bom_quantity')) has-error @endif'>
        <label for='bom_quantity' class='col-sm-2 control-label'>Product Code</label>
        <div class='col-sm-10'>
			{{ Form::label('product_name', $bill_material->product->product_code, ['class'=>'form-control','placeholder'=>'',]) }}
        </div>
    </div>

	<div class='form-group  @if ($errors->has('quantity')) has-error @endif'>
		{{ Form::label('quantity', 'Quantity',['class'=>'col-sm-4 control-label']) }}
		<div class='col-sm-8'>
			<div class='input-group'>
			{{ Form::text('bom_quantity', null, ['class'=>'form-control','placeholder'=>'']) }}
			@if ($errors->has('quantity')) <p class="help-block">{{ $errors->first('quantity') }}</p> @endif
				<div class='input-group-addon'>{{ $bill_material->product->unitMeasure->unit_shortname }}</div>
			</div>
		</div>
	</div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/bill_materials/index/{{ $bill_material->product_code }}" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
