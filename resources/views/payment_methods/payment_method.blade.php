
    <div class='form-group  @if ($errors->has('payment_name')) has-error @endif'>
        <label for='payment_name' class='col-sm-3 control-label'>Name<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('payment_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
            @if ($errors->has('payment_name')) <p class="help-block">{{ $errors->first('payment_name') }}</p> @endif
        </div>
    </div>

	<div class='form-group  @if ($errors->has('gl_code')) has-error @endif'>
		<label for='gl_code' class='col-sm-3 control-label'>GL Code</label>
		<div class='col-sm-9'>
			{{ Form::select('gl_code', $general_ledger,null, ['id'=>'gl_code', 'class'=>'form-control','maxlength'=>'20']) }}
			@if ($errors->has('gl_code')) <p class="help-block">{{ $errors->first('gl_code') }}</p> @endif
		</div>
	</div>
    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/payment_methods" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
