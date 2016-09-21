
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
            {{ Form::select('type_code', $patient_type, null, ['id'=>'type_code','onchange'=>'checkPatientType()','class'=>'form-control','maxlength'=>'20']) }}
        </div>
    </div>
    <div class='form-group  @if ($errors->has('sponsor_code')) has-error @endif'>
        {{ Form::label('sponsor_code', 'Sponsor',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::select('sponsor_code', $sponsor, null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('sponsor_code')) <p class="help-block">{{ $errors->first('sponsor_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('sponsor_id')) has-error @endif'>
        <label for='encounter_id' class='col-sm-3 control-label'>Sponsor ID</label>
        <div class='col-sm-9'>
            {{ Form::text('sponsor_id', null, ['id'=>'sponsor_id','class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('sponsor_id')) <p class="help-block">{{ $errors->first('sponsor_id') }}</p> @endif
			<small>Employee, insurance or third party payor identification stated above<small>
        </div>
    </div>

	{{ Form::hidden('patient_id', $patient->patient_id) }}

<script>
	document.getElementById('triage').disabled = true;
	document.getElementById('sponsor_code').disabled = true;
	document.getElementById('sponsor_id').disabled = true;

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

	function checkPatientType() {
		type = document.getElementById('type_code').value;
		if (type == 'public') {
			document.getElementById('sponsor_code').value = '';
			document.getElementById('sponsor_id').value = '';
			document.getElementById('sponsor_code').disabled = true;
			document.getElementById('sponsor_id').disabled = true;
		} else {
			document.getElementById('sponsor_code').disabled = false;
			document.getElementById('sponsor_id').disabled = false;
		}
	}

	function enableInputs() {
			document.getElementById('sponsor_code').disabled = false;
			document.getElementById('sponsor_id').disabled = false;
	}

	checkPatientType();
</script>
