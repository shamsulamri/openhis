@extends('layouts.app')

@section('content')
<h1>
New Patient Dependant
</h1>
@include('common.errors')
<br>
{{ Form::model($patient_dependant, ['url'=>'patient_dependants', 'class'=>'form-horizontal']) }} 
    
	@include('patient_dependants.patient_dependant')
{{ Form::close() }}

@endsection
