@extends('layouts.app')
@section('content')
@include('patients.id_only')
<h1>Edit Queue</h1>


{{ Form::model($queue, ['route'=>['queues.update',$queue->queue_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
	@include('queues.queue')
    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/queues" role="button">Cancel</a>
            {{ Form::submit('Save', ['id'=>'save','class'=>'btn btn-primary']) }}
        </div>
    </div>
{{ Form::close() }}

@endsection
