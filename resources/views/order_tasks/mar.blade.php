    <div class='form-group  @if ($errors->has('product_code')) has-error @endif'>
        <label for='product_name' class='col-sm-3 control-label'>Item</label>
        <div class='col-sm-9'>
            {{ Form::label('product_name', $product->product_name, ['class'=>'form-control','placeholder'=>'',]) }}
        </div>
    </div>
    <div class='form-group  @if ($errors->has('product_code')) has-error @endif'>
        <label for='user' class='col-sm-3 control-label'>Ordered By</label>
        <div class='col-sm-9'>
            {{ Form::label('user', $order_task->user->name, ['class'=>'form-control','placeholder'=>'',]) }}
        </div>
    </div>

   <div class='form-group  @if ($errors->has('order_quantity_supply')) has-error @endif'>
		<label for='order_quantity_supply' class='col-sm-3 control-label'>Total Unit:<span style='color:red;'> *</span></label>
		<div class='col-sm-9'>
			{{ Form::text('order_quantity_supply', null, ['class'=>'form-control','placeholder'=>'',]) }}
			@if ($errors->has('order_quantity_supply')) <p class="help-block">{{ $errors->first('order_quantity_supply') }}</p> @endif
		</div>
	</div>
	
    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
			<a class="btn btn-default" href="/medication_record/mar/{{ $order_task->encounter_id }}" role="button">Back</a>
        </div>
    </div>
            {{ Form::hidden('consultation_id', null) }}
            {{ Form::hidden('mar', true) }}


