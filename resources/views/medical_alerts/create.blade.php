@extends('layouts.app')

@section('content')
@include('consultations.panel')
@include('common.errors')

{{ Form::model($medical_alert, ['url'=>'medical_alerts', 'class'=>'form-horizontal']) }} 
	@include('medical_alerts.medical_alert')
{{ Form::close() }}

@endsection
