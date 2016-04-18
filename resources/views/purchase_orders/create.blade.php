@extends('layouts.app')

@section('content')
@include('common.errors')
{{ Form::model($purchase_order, ['url'=>'purchase_orders', 'class'=>'form-horizontal']) }} 
	@include('purchase_orders.purchase_order')
{{ Form::close() }}

@endsection
