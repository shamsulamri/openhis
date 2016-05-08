@extends('layouts.app')
@section('content')
@include('patients.id')
@include('common.errors')
{{ Form::model($queue, ['route'=>['queues.update',$queue->queue_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
	@include('queues.queue')
{{ Form::close() }}

@endsection
