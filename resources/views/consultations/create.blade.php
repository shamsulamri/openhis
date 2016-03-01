@extends('layouts.app')

@section('content')
<h1>
New Consultation
</h1>
@include('common.errors')
<br>
{{ Form::model($consultation, ['url'=>'consultations', 'class'=>'form-horizontal']) }} 
	@include('consultations.consultation')
{{ Form::close() }}

@endsection
