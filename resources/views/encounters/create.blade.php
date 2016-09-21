@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>New Encounter</h1>
@include('common.errors')
{{ Form::model($encounter, ['url'=>'encounters', 'class'=>'form-horizontal']) }} 
	@include('encounters.encounter')
    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/patients/{{ $patient->patient_id }}" role="button">Cancel</a>
            {{ Form::submit('Next', ['onclick'=>'enableInputs()','class'=>'btn btn-primary']) }}
        </div>
    </div>		
{{ Form::close() }}

@endsection
