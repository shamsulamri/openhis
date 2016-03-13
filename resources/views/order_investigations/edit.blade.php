@extends('layouts.app')

@section('content')
@include('patients.label')
@include('consultations.panel')
@include('common.errors')

{{ Form::model($order_investigation, ['route'=>['order_investigations.update',$order_investigation->id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
	@include('order_investigations.order_investigation')
{{ Form::close() }}

@endsection
