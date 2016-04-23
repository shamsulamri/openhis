
    <div class='form-group  @if ($errors->has('bom_quantity')) has-error @endif'>
        <label for='bom_quantity' class='col-sm-2 control-label'>Quantity<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
					<div class='input-group'>
							{{ Form::text('bom_quantity', null, ['class'=>'form-control','placeholder'=>'',]) }}
							@if ($errors->has('bom_quantity')) <p class="help-block">{{ $errors->first('bom_quantity') }}</p> @endif
							<div class='input-group-addon'>{{ $unit }}<div>
					</div>
        </div>
    </div>

	<br>
    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/bill_materials/index/{{ $bill_material->product_code }}" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
