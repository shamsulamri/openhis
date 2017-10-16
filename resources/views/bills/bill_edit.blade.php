@extends('layouts.app')

@section('content')
@include('patients.id_only')


<h1>Billing Information</h1>
<hr>
<div class='alert alert-danger'>
<strong>Warning!</strong> all billing changes will be lost when saved.
</div>
<form id='form' action='/bill/bill_update/{{ $encounter->encounter_id }}' method='post' class='form-horizontal'>
    <div class='form-group  @if ($errors->has('type_code')) has-error @endif'>
        {{ Form::label('Type', 'Type',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::select('type_code', $patient_type, $encounter->type_code, ['id'=>'type_code','onchange'=>'checkPatientType()','class'=>'form-control','maxlength'=>'20']) }}
        </div>
    </div>
    <div class='form-group  @if ($errors->has('sponsor_code')) has-error @endif'>
        {{ Form::label('sponsor_code', 'Sponsor',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::select('sponsor_code', $sponsor, $encounter->sponsor_code, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('sponsor_code')) <p class="help-block">{{ $errors->first('sponsor_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('sponsor_id')) has-error @endif'>
        <label for='encounter_id' class='col-sm-3 control-label'>Membership</label>
        <div class='col-sm-9'>
            {{ Form::text('sponsor_id', $encounter->sponsor_id, ['id'=>'sponsor_id','class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('sponsor_id')) <p class="help-block">{{ $errors->first('sponsor_id') }}</p> @endif
			<small>Employee, insurance or third party payor identification stated above</small>
        </div>
    </div>
    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/bill_items/{{ $encounter->encounter_id }}" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>		
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
	<input type='hidden' name="patient_id" value="{{ $encounter->patient_id }}">
	<input type='hidden' name="encounter_code" value="{{ $encounter->encounter_code }}">
</form>

<script>
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

	checkPatientType();
</script>
@endsection
