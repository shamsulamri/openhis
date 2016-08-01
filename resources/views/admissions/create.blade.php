@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>New Encounter</h1>
@if (count($errors) > 0)
    <!-- form error list -->
    <div class="alert alert-danger">
			please correct the errors highlighted below.
    </div>
@endif
{{ Form::model($admission, ['url'=>'admissions', 'class'=>'form-horizontal']) }} 
	@include('admissions.admission')
    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/patients/{{ $encounter->patient_id }}" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
{{ Form::close() }}

@endsection
