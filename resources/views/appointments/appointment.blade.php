
    <div class='form-group  @if ($errors->has('appointment_slot')) has-error @endif'>
        <label for='appointment_slot' class='col-sm-2 control-label'>Service</label>
        <div class='col-sm-10'>
            {{ Form::label('service_id', $service_name, ['class'=>'form-control','placeholder'=>'',]) }}
        </div>
    </div>

    <div class='form-group  @if ($errors->has('appointment_slot')) has-error @endif'>
        <label for='appointment_slot' class='col-sm-2 control-label'>Slot</label>
        <div class='col-sm-10'>
            {{ Form::label('appointment_slot', $appointment_datetime->format('l, d F Y, H:i'), ['class'=>'form-control','placeholder'=>'',]) }}
        </div>
    </div>

    <div class='form-group  @if ($errors->has('appointment_description')) has-error @endif'>
        <label for='appointment_description' class='col-sm-2 control-label'>Description</label>
        <div class='col-sm-10'>
            {{ Form::textarea('appointment_description', null, ['class'=>'form-control','rows'=>'4']) }}
            @if ($errors->has('appointment_description')) <p class="help-block">{{ $errors->first('appointment_description') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/appointments" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
    
	{{ Form::hidden('patient_id', null) }}
	{{ Form::hidden('appointment_slot', null) }}
	{{ Form::hidden('service_id', null) }}
