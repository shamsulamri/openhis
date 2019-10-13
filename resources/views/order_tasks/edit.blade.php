@extends('layouts.app')

@section('content')
@include('patients.id_only')

		@if ($mar)
<h1>
MAR
</h1>
		@else
<h1>
Order
</h1>
		@endif
<br>
{{ Form::model($order_task, ['route'=>['order_tasks.update',$order_task->order_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
		@if ($mar)
			@include('order_tasks.mar')
		@else
			@include('order_tasks.order_task')
		@endif
{{ Form::close() }}

@endsection
