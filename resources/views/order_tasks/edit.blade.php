@extends('layouts.app')

@section('content')
@include('patients.id')
<h2>
Task Outcome
</h2>
<br>
@include('common.errors')
<br>
{{ Form::model($order_task, ['route'=>['order_tasks.update',$order_task->order_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('order_tasks.order_task')
{{ Form::close() }}

@endsection
