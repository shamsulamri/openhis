@extends('layouts.app')

@section('content')
<h1>
New Purchase Order
</h1>
@include('common.errors')
<br>
{{ Form::model($purchase_order, ['url'=>'purchase_orders', 'class'=>'form-horizontal']) }} 
	@include('purchase_orders.purchase_order')
{{ Form::close() }}

@endsection
