@extends('layouts.app2')

@section('content')
@include('common.errors')

{{ Form::model($purchase_order, ['route'=>['purchase_orders.update',$purchase_order->purchase_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
	@include('purchase_orders.purchase_order')
{{ Form::close() }}

@endsection
