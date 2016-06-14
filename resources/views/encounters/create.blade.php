@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>New Encounter</h1>
<br>
@include('common.errors')
{{ Form::model($encounter, ['url'=>'encounters', 'class'=>'form-horizontal']) }} 
	@include('encounters.encounter')
    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/patients/{{ $patient->patient_id }}" role="button">Cancel</a>
            {{ Form::submit('Next', ['class'=>'btn btn-primary']) }}
        </div>
    </div>		
{{ Form::close() }}

@endsection
