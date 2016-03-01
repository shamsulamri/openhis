@extends('layouts.app')

@section('content')
<h1>
Edit Medical Alert
</h1>
@include('common.errors')
<br>
{{ Form::model($medical_alert, ['route'=>['medical_alerts.update',$medical_alert->alert_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('medical_alerts.medical_alert')
{{ Form::close() }}

@endsection
