@extends('layouts.app')

@section('content')
@include('consultations.panel')
<h1>Medical Certificate</h1>
@include('common.notification')
@include('common.errors')
<br>
{{ Form::model($medical_certificate, ['url'=>'medical_certificates', 'class'=>'form-horizontal']) }} 
	@include('medical_certificates.medical_certificate')
{{ Form::close() }}

@endsection
