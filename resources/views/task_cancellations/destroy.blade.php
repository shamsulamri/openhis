@extends('layouts.app')

@section('content')
<h1>
Delete Task Cancellation
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $task_cancellation->order_id }}
{{ Form::open(['url'=>'task_cancellations/'.$task_cancellation->cancel_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/task_cancellations" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
