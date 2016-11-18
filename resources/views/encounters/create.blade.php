@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>New Encounter</h1>
{{ Form::model($encounter, ['id'=>'myForm','url'=>'encounters', 'class'=>'form-horizontal']) }} 
	@include('encounters.encounter')
    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
        </div>
    </div>		
	<div class='pull-right'>
            <a class="btn btn-default" href="/patients/{{ $patient->patient_id }}/edit" role="button">Cancel</a>
			<!--
            <a class="btn btn-primary" href="javascript:postForm()" role="button">Save</a>
			-->
			{{ Form::submit('Save', ['id'=>'btnSave','onclick'=>'disableForm(false)','class'=>'btn btn-primary']) }}
	</div>
{{ Form::close() }}

@endsection
