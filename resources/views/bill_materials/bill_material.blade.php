
    <div class='form-group  @if ($errors->has('product_code')) has-error @endif'>
        <label for='bom_quantity' class='col-sm-3 control-label'>Product Code</label>
        <div class='col-sm-9'>
			{{ Form::label('product_name', $bill_material->product->product_code, ['class'=>'form-control','placeholder'=>'',]) }}
        </div>
    </div>

	<div class='form-group  @if ($errors->has('bom_quantity')) has-error @endif'>
		{{ Form::label('quantity', 'Quantity',['class'=>'col-sm-4 control-label']) }}
		<div class='col-sm-8'>
			<div class='input-group'>
			{{ Form::text('bom_quantity', $bill_material->bom_quantity, ['class'=>'form-control','placeholder'=>'']) }}
				<div class='input-group-addon'>{{ $unit }}</div>
			</div>
			@if ($errors->has('bom_quantity')) <p class="help-block">{{ $errors->first('bom_quantity') }}</p> @endif
		</div>
	</div>

    <div class='form-group  @if ($errors->has('unit_code')) has-error @endif'>
        <label for='bom_quantity' class='col-sm-3 control-label'>Unit Price</label>
        <div class='col-sm-9'>
				{{ Form::select('unit_code', $uom_list, $bill_material->unit_code, ['id'=>'uom_list', 'onchange'=>'uomChanged()']) }}
        </div>
    </div>
    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/bill_materials/index/{{ $bill_material->product_code }}" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
