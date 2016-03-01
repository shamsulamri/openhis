@extends('layouts.app')

@section('content')
<h1>
Edit Queue
</h1>
@include('common.errors')
<br>
{{ Form::model($queue, ['route'=>['queues.update',$queue->queue_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
	@include('queues.queue')
{{ Form::close() }}

@endsection
