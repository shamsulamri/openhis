@extends('layouts.app')

@section('content')
@include('patients.label')
@include('consultations.panel')

{{ Form::model($order, ['route'=>['orders.update',$order->order_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('orders.order')
{{ Form::close() }}

@endsection
