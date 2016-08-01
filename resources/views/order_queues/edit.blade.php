@extends('layouts.app')

@section('content')
<h1>
Edit Queue Order
</h1>

include('common.errors')
{{ Form::model($order_queue, ['route'=>['order_queues.update',$order_queue->post_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('order_queues.order_queue')
{{ Form::close() }}

@endsection
