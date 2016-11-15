@extends('layouts.app')

@section('content')
<h1>
Edit Appointment Service
</h1>

<br>
{{ Form::model($appointment_service, ['route'=>['appointment_services.update',$appointment_service->service_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('appointment_services.appointment_service')
{{ Form::close() }}

@endsection
