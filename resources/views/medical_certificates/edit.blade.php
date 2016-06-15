@extends('layouts.app')

@section('content')
@include('consultations.panel')
@include('common.errors')
@if (Session::has('message'))
	<br>
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
{{ Form::model($medical_certificate, ['route'=>['medical_certificates.update',$medical_certificate->mc_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('medical_certificates.medical_certificate')
{{ Form::close() }}

@endsection
