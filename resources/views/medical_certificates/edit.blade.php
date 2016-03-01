@extends('layouts.app')

@section('content')
<h1>
Edit Medical Certificate
</h1>
@include('common.errors')
<br>
{{ Form::model($medical_certificate, ['route'=>['medical_certificates.update',$medical_certificate->mc_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('medical_certificates.medical_certificate')
{{ Form::close() }}

@endsection
