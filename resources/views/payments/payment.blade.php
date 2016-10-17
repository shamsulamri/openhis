
	@if ($billHelper->paymentOutstanding($patient->patient_id)>0)
    <div class='form-group  @if ($errors->has('payment_outstanding')) has-error @endif'>
        <label class='col-sm-3 control-label'><br>Balance</label>
        <div class='col-sm-9'>
        	<h1>{{ $billHelper->paymentOutstanding($patient->patient_id) }}</h1>
        </div>
    </div>
	@endif

    <div class='form-group  @if ($errors->has('payment_amount')) has-error @endif'>
        <label for='payment_amount' class='col-sm-3 control-label'>Amount<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('payment_amount', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('payment_amount')) <p class="help-block">{{ $errors->first('payment_amount') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('payment_code')) has-error @endif'>
        <label for='payment_code' class='col-sm-3 control-label'>Type<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::select('payment_code', $payment_methods,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('payment_code')) <p class="help-block">{{ $errors->first('payment_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('payment_description')) has-error @endif'>
        {{ Form::label('payment_description', 'Note',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::textarea('payment_description', null, ['class'=>'form-control','placeholder'=>'','rows'=>'4']) }}
            @if ($errors->has('payment_description')) <p class="help-block">{{ $errors->first('payment_description') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="javascript:goBack()" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>

	@if ($payment->encounter_id>0)
		{{ Form::hidden('encounter_id', null) }}
	@endif
	@if ($payment->encounter_id==0)
    	{{ Form::hidden('encounter_id', $payment->encounter_id) }}
	@endif
    {{ Form::hidden('patient_id', null) }}
