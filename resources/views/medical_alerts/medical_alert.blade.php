
<h1>Medical Alerts</h1>
<br>

    <div class='form-group  @if ($errors->has('alert_description')) has-error @endif'>
        <label for='alert_description' class='col-sm-2 control-label'>Description<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::textarea('alert_description', null, ['class'=>'form-control','placeholder'=>'','rows'=>'4']) }}
            @if ($errors->has('alert_description')) <p class="help-block">{{ $errors->first('alert_description') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('alert_public')) has-error @endif'>
        <label for='alert_public' class='col-sm-2 control-label'></label>
        <div class='col-sm-10'>
			{{ Form::checkbox('alert_public', '1') }} Make this information public.
            @if ($errors->has('alert_public')) <p class="help-block">{{ $errors->first('alert_public') }}</p> @endif
        </div>
    </div>
    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/medical_alerts" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
	{{ Form::hidden('patient_id', $patient->patient_id) }}
	{{ Form::hidden('consultation_id', $consultation->consultation_id) }}
