
    <div class='form-group  @if ($errors->has('patient_id')) has-error @endif'>
        <label for='patient_id' class='col-sm-2 control-label'>patient_id<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('patient_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('patient_id')) <p class="help-block">{{ $errors->first('patient_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('service_code')) has-error @endif'>
        <label for='service_code' class='col-sm-2 control-label'>service_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('service_code', $service,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('service_code')) <p class="help-block">{{ $errors->first('service_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('appointment_slot')) has-error @endif'>
        <label for='appointment_slot' class='col-sm-2 control-label'>appointment_slot<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('appointment_slot', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('appointment_slot')) <p class="help-block">{{ $errors->first('appointment_slot') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/appointments" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
