@extends('layouts.app')

@section('content')
<h1>
Task Cancellation
</h1>
<br>
@include('orders.panel')
@include('common.errors')

{{ Form::model($task_cancellation, ['url'=>'task_cancellations', 'class'=>'form-horizontal']) }} 
	@include('task_cancellations.task_cancellation')
{{ Form::close() }}

@endsection
