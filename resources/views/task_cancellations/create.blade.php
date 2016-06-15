@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>
Task Cancellation
</h1>
<br>
@include('common.errors')

{{ Form::model($task_cancellation, ['url'=>'task_cancellations', 'class'=>'form-horizontal']) }} 
	@include('task_cancellations.task_cancellation')
{{ Form::close() }}

@endsection
