@extends('layouts.app')
@section('content')
@include('patients.id_only')
<h1>
@if (empty($refer)) 
Edit Queue
@else
Refer Patient
@endif
</h1>


{{ Form::model($queue, ['route'=>['queues.update',$queue->queue_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
	@include('queues.queue')
    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
		@if (empty($refer)) 
            <a class="btn btn-default" href="/queues" role="button">Cancel</a>
		@else
            <a class="btn btn-default" href="/patient_lists" role="button">Cancel</a>
		@endif
            {{ Form::submit('Save', ['id'=>'save','class'=>'btn btn-primary']) }}
        </div>
    </div>
	{{ Form::hidden('refer', $refer) }}
{{ Form::close() }}

@endsection
