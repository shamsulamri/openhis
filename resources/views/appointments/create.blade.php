@extends('layouts.app')

@section('content')
@include('patients.id')
<h2>
New Appointment
</h2>
@include('common.errors')
<br>
{{ Form::model($appointment, ['url'=>'appointments', 'class'=>'form-horizontal']) }} 
    
	@include('appointments.appointment')
{{ Form::close() }}

@endsection
