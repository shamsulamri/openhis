@extends('layouts.app')

@section('content')
@include('patients.id')
<h2>
Edit Appointment
</h2>
@include('common.errors')
<br>
{{ Form::model($appointment, ['route'=>['appointments.update',$appointment->appointment_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('appointments.appointment')
{{ Form::close() }}

@endsection
