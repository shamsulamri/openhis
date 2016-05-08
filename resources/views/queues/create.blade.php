@extends('layouts.app')

@section('content')
@include('patients.id')
@include('common.errors')
{{ Form::model($queue, ['url'=>'queues', 'class'=>'form-horizontal']) }} 
	@include('queues.queue')
{{ Form::close() }}

@endsection
