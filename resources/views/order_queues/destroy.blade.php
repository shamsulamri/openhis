@extends('layouts.app')

@section('content')
<h1>
Delete Queue Order
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $order_queue->consultation_id }}
{{ Form::open(['url'=>'order_queues/'.$order_queue->post_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/order_queues" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
