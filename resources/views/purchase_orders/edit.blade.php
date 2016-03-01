@extends('layouts.app')

@section('content')
<h1>
Edit Purchase Order
</h1>
@include('common.errors')
<br>
{{ Form::model($purchase_order, ['route'=>['purchase_orders.update',$purchase_order->purchase_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
	@include('purchase_orders.purchase_order')
{{ Form::close() }}

@endsection
