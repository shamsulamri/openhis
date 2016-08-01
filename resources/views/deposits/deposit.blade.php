
    <div class='form-group  @if ($errors->has('deposit_amount')) has-error @endif'>
        <label for='deposit_amount' class='col-sm-3 control-label'>Amount<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('deposit_amount', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('deposit_amount')) <p class="help-block">{{ $errors->first('deposit_amount') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('payment_code')) has-error @endif'>
        <label for='payment_code' class='col-sm-3 control-label'>Payment Method<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::select('payment_code', $payment,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('payment_code')) <p class="help-block">{{ $errors->first('payment_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('deposit_description')) has-error @endif'>
        {{ Form::label('deposit_description', 'Note',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::textarea('deposit_description', null, ['class'=>'form-control','placeholder'=>'','rows'=>'4']) }}
            @if ($errors->has('deposit_description')) <p class="help-block">{{ $errors->first('deposit_description') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/patients/{{ $encounter->patient_id }}" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
    
	{{ Form::hidden('encounter_id', $encounter->encounter_id) }}
