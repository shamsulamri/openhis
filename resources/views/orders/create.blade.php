@extends('layouts.app')

@section('content')
@include('patients.label')
@include('consultations.panel')
@include('common.errors')

{{ Form::model($order, ['url'=>'orders', 'class'=>'form-horizontal']) }} 
	@include('orders.order')
{{ Form::close() }}

@endsection
