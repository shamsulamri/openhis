@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>New Encounter</h1>
@include('common.errors')

{{ Form::model($admission, ['url'=>'admissions', 'class'=>'form-horizontal']) }} 
	@include('admissions.admission')
    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/patients/{{ $encounter->patient_id }}" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
{{ Form::close() }}

@endsection
