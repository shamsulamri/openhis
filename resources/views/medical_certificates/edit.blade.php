@extends('layouts.app')

@section('content')
@include('consultations.panel')
<h1>Medical Certificate</h1>
<br>
@if (count($errors) > 0)
	@include('common.errors')
@else
	@include('common.notification')
@endif
{{ Form::model($medical_certificate, ['route'=>['medical_certificates.update',$medical_certificate->mc_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
	@include('medical_certificates.medical_certificate')
{{ Form::close() }}

@endsection
