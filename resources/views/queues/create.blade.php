@extends('layouts.app')

@section('content')
<h1>
New Queue
</h1>
@include('common.errors')
<br>
{{ Form::model($queue, ['url'=>'queues', 'class'=>'form-horizontal']) }} 
	@include('queues.queue')
{{ Form::close() }}

@endsection
