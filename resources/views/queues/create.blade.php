@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>New Encounter</h1>
<br>
@include('common.errors')
{{ Form::model($queue, ['url'=>'queues', 'class'=>'form-horizontal']) }} 
	@include('queues.queue')
{{ Form::close() }}

@endsection
