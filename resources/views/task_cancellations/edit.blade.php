@extends('layouts.app')

@section('content')
<h1>
Edit Task Cancellation
</h1>
@include('common.errors')
<br>
{{ Form::model($task_cancellation, ['route'=>['task_cancellations.update',$task_cancellation->cancel_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('task_cancellations.task_cancellation')
{{ Form::close() }}

@endsection
