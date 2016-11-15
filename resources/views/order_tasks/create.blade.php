@extends('layouts.app')

@section('content')
<h1>
New Order Task
</h1>

<br>
{{ Form::model($order_task, ['url'=>'order_tasks', 'class'=>'form-horizontal']) }} 
    
	@include('order_tasks.order_task')
{{ Form::close() }}

@endsection
