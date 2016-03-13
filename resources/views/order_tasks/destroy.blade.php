@extends('layouts.app')

@section('content')
<h1>
Delete Order Task
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $order_task->product_code }}
{{ Form::open(['url'=>'order_tasks/'.$order_task->order_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/order_tasks" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
