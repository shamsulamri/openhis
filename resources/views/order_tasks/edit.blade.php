@extends('layouts.app')

@section('content')
@include('patients.id_only')
<h1>
Order
</h1>

<br>
{{ Form::model($order_task, ['route'=>['order_tasks.update',$order_task->order_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('order_tasks.order_task')
{{ Form::close() }}

@endsection
