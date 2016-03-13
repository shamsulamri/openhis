@extends('layouts.app')

@section('content')
@include('patients.label')
@include('consultations.panel')
@include('common.errors')

{{ Form::model($order_investigation, ['url'=>'order_investigations', 'class'=>'form-horizontal']) }} 
	@include('order_investigations.order_investigation')
{{ Form::close() }}

@endsection
