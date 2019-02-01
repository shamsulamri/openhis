
    <div class='form-group'>
        <label for='batch_number' class='col-sm-2 control-label'>Product</label>
        <div class='col-sm-10'>
			{{ Form::label('product_name', $inventory_batch->product?$inventory_batch->product->product_name:'-' , ['class'=>'form-control']) }}
        </div>
    </div>

    <div class='form-group'>
        <label for='batch_number' class='col-sm-2 control-label'>Code</label>
        <div class='col-sm-10'>
			{{ Form::label('product_code', $inventory_batch->product_code?:'-' , ['class'=>'form-control']) }}
        </div>
    </div>

    <div class='form-group  @if ($errors->has('batch_number')) has-error @endif'>
        <label for='batch_number' class='col-sm-2 control-label'>Batch Number<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('batch_number', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('batch_number')) <p class="help-block">{{ $errors->first('batch_number') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('batch_expiry_date')) has-error @endif'>
        {{ Form::label('batch_expiry_date', 'Expiry Date',['class'=>'col-md-2 control-label']) }}
        <div class='col-md-10'>
			<div class="input-group date">
				<input data-mask="99/99/9999" name="batch_expiry_date" id="batch_expiry_date" value='{{ $inventory_batch->batch_expiry_date }}' type="text" class="form-control">
				<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
			</div>
            @if ($errors->has('batch_expiry_date')) <p class="help-block">{{ $errors->first('batch_expiry_date') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('batch_description')) has-error @endif'>
        {{ Form::label('batch_description', 'Description',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('batch_description', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'65535']) }}
            @if ($errors->has('batch_description')) <p class="help-block">{{ $errors->first('batch_description') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/inventory_batches/product/{{ $product->product_code }}" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
	
{{ Form::hidden('product_code', $inventory_batch->product_code) }}
<script>
		$('#batch_expiry_date').datepicker({
				format: "dd/mm/yyyy",
				todayBtn: "linked",
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true
		});
</script>
