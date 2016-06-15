@extends('layouts.app')

@section('content')
@include('consultations.panel')
@include('common.errors')

{{ Form::model($medical_alert, ['route'=>['medical_alerts.update',$medical_alert->alert_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
	@include('medical_alerts.medical_alert')
{{ Form::close() }}

@endsection
