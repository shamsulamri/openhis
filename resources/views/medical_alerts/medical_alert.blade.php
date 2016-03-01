
    <div class='form-group  @if ($errors->has('patient_id')) has-error @endif'>
        <label for='patient_id' class='col-sm-2 control-label'>patient_id<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('patient_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('patient_id')) <p class="help-block">{{ $errors->first('patient_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('alert_description')) has-error @endif'>
        <label for='alert_description' class='col-sm-2 control-label'>alert_description<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('alert_description', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'200']) }}
            @if ($errors->has('alert_description')) <p class="help-block">{{ $errors->first('alert_description') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/medical_alerts" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
