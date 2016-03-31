@extends('layouts.app')

@section('content')
@include('orders.panel')
@include('common.errors')

{{ Form::model($order_investigation, ['url'=>'order_investigations', 'class'=>'form-horizontal']) }} 
	@include('order_investigations.order_investigation')
{{ Form::close() }}

@endsection
