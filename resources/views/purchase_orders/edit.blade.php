@extends('layouts.app2')

@section('content')

{{ Form::model($purchase_order, ['route'=>['purchase_orders.update',$purchase_order->purchase_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
	@include('purchase_orders.purchase_order')
{{ Form::close() }}

@endsection
