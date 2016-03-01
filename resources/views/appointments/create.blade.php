@extends('layouts.app')

@section('content')
<h1>
New Appointment
</h1>
@include('common.errors')
<br>
{{ Form::model($appointment, ['url'=>'appointments', 'class'=>'form-horizontal']) }} 
    
	@include('appointments.appointment')
{{ Form::close() }}

@endsection
