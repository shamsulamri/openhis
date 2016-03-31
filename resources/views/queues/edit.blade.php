@extends('layouts.app')
@section('content')
@include('patients.id')
<h2>Edit Queue</h2>
@include('common.errors')
<br>
{{ Form::model($queue, ['route'=>['queues.update',$queue->queue_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
	@include('queues.queue')
{{ Form::close() }}

@endsection
