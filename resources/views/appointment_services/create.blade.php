@extends('layouts.app')

@section('content')
<h1>
New Appointment Service
</h1>

<br>
{{ Form::model($appointment_service, ['url'=>'appointment_services', 'class'=>'form-horizontal']) }} 
    
	@include('appointment_services.appointment_service')
{{ Form::close() }}

@endsection
