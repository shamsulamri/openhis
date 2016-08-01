@extends('layouts.app')

@section('content')
<h1>
New Queue Order
</h1>
@include('common.errors')
<br>
{{ Form::model($order_queue, ['url'=>'order_queues', 'class'=>'form-horizontal']) }} 
    
	@include('order_queues.order_queue')
{{ Form::close() }}

@endsection
