@extends('layouts.app')

@section('content')
<h1>
Edit Order Task
</h1>
@include('orders.panel')
@include('common.errors')
<br>
{{ Form::model($order_task, ['route'=>['order_tasks.update',$order_task->order_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('order_tasks.order_task')
{{ Form::close() }}

@endsection
