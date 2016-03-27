@extends('layouts.app')

@section('content')
@include('patients.id')
<div class='page-header'>
		<h1>New Queue</h1>
</div>
@include('common.errors')
<br>
{{ Form::model($queue, ['url'=>'queues', 'class'=>'form-horizontal']) }} 
	@include('queues.queue')
{{ Form::close() }}

@endsection
