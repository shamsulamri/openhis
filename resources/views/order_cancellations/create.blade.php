@extends('layouts.app2')

@section('content')

<h3>Order Cancellation</h3>
@include('common.errors')

{{ Form::model($order_cancellation, ['url'=>'order_cancellations', 'class'=>'form-horizontal']) }} 
	@include('order_cancellations.order_cancellation')
{{ Form::close() }}

@endsection
