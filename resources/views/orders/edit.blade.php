@extends('layouts.app')

@section('content')
@include('patients.label')
@include('consultations.panel')
<h3>
{{ $product->product_name }}
</h3>
@include('common.errors')
<br>
{{ Form::model($order, ['route'=>['orders.update',$order->order_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('orders.order')
{{ Form::close() }}

@endsection
