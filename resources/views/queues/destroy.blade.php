@extends('layouts.app')

@section('content')
<h1>
Delete Queue
</h1>
@include('common.errors')
<br>
<h4>
Are you sure you want to delete the selected record ?
<br>
<br>
<strong>
{{ $queue->encounter->patient->patient_name }} 
</strong>
queue at 
<strong>
{{ $queue->location->location_name }}
</strong>
<br>
<br>
{{ Form::open(['url'=>'queues/'.$queue->queue_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/queues" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h4>
@endsection
