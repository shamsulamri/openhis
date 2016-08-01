
<!--
<h4>
<ul class="nav nav-tabs nav-justified">
  <li role="presentation" class="active"><a href="#">Step 1: Encounter</a></li>
  <li role="presentation" class='disabled'><a href="#">&nbsp;</a></li>
  <li role="presentation" class='disabled'><a href="#">&nbsp;</a></li>
</ul>
</h4>
-->
	<div class='page-header'>
		<h3>Encounter</h3>
	</div>
	<div class='form-group  @if ($errors->has('encounter_code')) has-error @endif'>
        <label for='encounter_code' class='col-sm-3 control-label'>Encounter<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
			{{ Form::select('encounter_code', $encounter_type, null, ['id'=>'encounter','class'=>'form-control','onchange'=>'checkTriage()']) }}
				<small>Define the encounter nature of the patient</small>
            @if ($errors->has('encounter_code')) <p class="help-block">{{ $errors->first('encounter_code') }}</p> @endif
        </div>
    </div>

	 <div class='form-group  @if ($errors->has('triage_code')) has-error @endif'>
        {{ Form::label('Triage', 'Triage',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::select('triage_code', $triage, null, ['id'=>'triage','class'=>'form-control','maxlength'=>'20']) }}
			<small>For emergency cases only</small>
        </div>
    </div>

	<div class='page-header'>
		<h3>Billing Information</h3>
	</div>

    <div class='form-group  @if ($errors->has('type_code')) has-error @endif'>
        {{ Form::label('Patient Type', 'Patient Type',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::select('type_code', $patient_type, null, ['class'=>'form-control','maxlength'=>'20']) }}
        </div>
    </div>
    <div class='form-group  @if ($errors->has('employer_code')) has-error @endif'>
        {{ Form::label('employer_code', 'Employer',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::select('employer_code', $employer, null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('employer_code')) <p class="help-block">{{ $errors->first('employer_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('employee_id')) has-error @endif'>
        <label for='encounter_id' class='col-sm-3 control-label'>Employee ID</label>
        <div class='col-sm-9'>
            {{ Form::text('employee_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('employee_id')) <p class="help-block">{{ $errors->first('employee_id') }}</p> @endif
        </div>
    </div>

	{{ Form::hidden('patient_id', $patient->patient_id) }}

<script>
	document.getElementById('triage').disabled = true;

	function checkTriage() {
		triage = document.getElementById('triage');
		encounter = document.getElementById('encounter').value;
		if (encounter=='emergency') {
				triage.disabled=false;
		} else {
				triage.value = '';
				triage.disabled=true;
		}
				
	}
</script>
