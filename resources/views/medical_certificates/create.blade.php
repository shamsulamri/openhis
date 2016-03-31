@extends('layouts.app')

@section('content')
@include('patients.label')
@include('common.errors')

{{ Form::model($medical_certificate, ['url'=>'medical_certificates', 'class'=>'form-horizontal']) }} 
	@include('medical_certificates.medical_certificate')
{{ Form::close() }}

@endsection
