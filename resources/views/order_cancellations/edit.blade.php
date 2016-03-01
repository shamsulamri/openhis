@extends('layouts.app')

@section('content')
<h1>
Edit Order Cancellation
</h1>
@include('common.errors')
<br>
{{ Form::model($order_cancellation, ['route'=>['order_cancellations.update',$order_cancellation->cancel_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('order_cancellations.order_cancellation')
{{ Form::close() }}

@endsection
