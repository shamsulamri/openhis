@extends('layouts.app')

@section('content')
<h1>
Edit Queue
</h1>
@include('common.errors')
<br>
{{ Form::model($queue, ['route'=>['queues.update',$queue->queue_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>queue_id<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('queue_id', $queue->queue_id, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('queues.queue')
{{ Form::close() }}

@endsection
