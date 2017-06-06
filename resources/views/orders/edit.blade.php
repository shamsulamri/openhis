@extends('layouts.app2')

@section('content')

{{ Form::model($order, ['route'=>['orders.update',$order->order_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('orders.order')
{{ Form::close() }}

@endsection
