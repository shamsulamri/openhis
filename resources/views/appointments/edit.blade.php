@extends('layouts.app')

@section('content')
<h1>
Edit Appointment
</h1>
@include('common.errors')
<br>
{{ Form::model($appointment, ['route'=>['appointments.update',$appointment->appointment_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('appointments.appointment')
{{ Form::close() }}

@endsection
