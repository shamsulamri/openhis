
	<div class='form-group  @if ($errors->has('encounter_code')) has-error @endif'>
        <label for='encounter_code' class='col-sm-2 control-label'>Encounter<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
			{{ Form::select('encounter_code', $encounter_type, null, ['class'=>'form-control']) }}
            @if ($errors->has('encounter_code')) <p class="help-block">{{ $errors->first('encounter_code') }}</p> @endif
        </div>
    </div>

	 <div class='form-group  @if ($errors->has('triage_code')) has-error @endif'>
        {{ Form::label('Triage', 'Triage',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('triage_code', $triage, null, ['class'=>'form-control','maxlength'=>'20']) }}
			<br>
			<small>For emergency cases only</small>
        </div>
    </div>

	<div class='page-header'>
		<h4>Billing & Insurance Detail</h4>
	</div>

    <div class='form-group  @if ($errors->has('type_code')) has-error @endif'>
        {{ Form::label('Patient Type', 'Patient Type',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('type_code', $patient_type, null, ['class'=>'form-control','maxlength'=>'20']) }}
        </div>
    </div>
    <div class='form-group  @if ($errors->has('employer_code')) has-error @endif'>
        {{ Form::label('employer_code', 'Employer',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('employer_code', $employer, null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('employer_code')) <p class="help-block">{{ $errors->first('employer_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('employee_id')) has-error @endif'>
        <label for='encounter_id' class='col-sm-2 control-label'>Employee ID</label>
        <div class='col-sm-10'>
            {{ Form::text('employee_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('employee_id')) <p class="help-block">{{ $errors->first('employee_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('organisation_code')) has-error @endif'>
        {{ Form::label('organisation_code', 'Organisation',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('organisation_code', $organisation, null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('organisation_code')) <p class="help-block">{{ $errors->first('organisation_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('organisation_id')) has-error @endif'>
        <label for='organisation_id' class='col-sm-2 control-label'>Membership ID</label>
        <div class='col-sm-10'>
            {{ Form::text('organisation_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('organisation_id')) <p class="help-block">{{ $errors->first('organisation_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/patients/{{ $patient->patient_id }}" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
			
	{{ Form::hidden('patient_id', $patient->patient_id) }}
