@extends('layouts.app')

@section('content')
<h1>
New Medical Alert
</h1>
@include('common.errors')
<br>
{{ Form::model($medical_alert, ['url'=>'medical_alerts', 'class'=>'form-horizontal']) }} 
    
	@include('medical_alerts.medical_alert')
{{ Form::close() }}

@endsection
