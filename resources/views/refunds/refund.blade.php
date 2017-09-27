
    <div class='form-group  @if ($errors->has('refund_type')) has-error @endif'>
        <label for='refund_type' class='col-sm-2 control-label'>Type<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
			{{ Form::select('refund_type', $refund_types,null , ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('refund_type')) <p class="help-block">{{ $errors->first('refund_type') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('refund_reference')) has-error @endif'>
        <label for='refund_type' class='col-sm-2 control-label'>Reference<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('refund_reference', null, ['class'=>'form-control','placeholder'=>'Bill or deposit reference ids separated by comma','maxlength'=>'100']) }}
            @if ($errors->has('refund_reference')) <p class="help-block">{{ $errors->first('refund_reference') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('refund_amount')) has-error @endif'>
        <label for='refund_amount' class='col-sm-2 control-label'>Amount<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('refund_amount', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('refund_amount')) <p class="help-block">{{ $errors->first('refund_amount') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('refund_description')) has-error @endif'>
        {{ Form::label('refund_description', 'Descripton',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('refund_description', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'65535']) }}
            @if ($errors->has('refund_description')) <p class="help-block">{{ $errors->first('refund_description') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/refund/transactions/{{ $patient->patient_id }}" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>

	{{ Form::hidden('patient_id', $patient->patient_id) }}
