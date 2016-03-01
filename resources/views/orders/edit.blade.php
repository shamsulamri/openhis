@extends('layouts.app')

@section('content')
<h1>
Edit Order
</h1>
@include('common.errors')
<br>
{{ Form::model($order, ['route'=>['orders.update',$order->order_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('orders.order')
{{ Form::close() }}

@endsection
