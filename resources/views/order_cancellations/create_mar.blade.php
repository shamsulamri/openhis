@extends('layouts.app')

@section('content')

@include('patients.id_only')
<h1>Order Cancellation</h1>
<br>


{{ Form::model($order_cancellation, ['url'=>'order_cancellations', 'class'=>'form-horizontal']) }} 
	@include('order_cancellations.order_cancellation')
{{ Form::close() }}

@endsection
