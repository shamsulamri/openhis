@extends('layouts.app')

@section('content')
<h1>
Delete Order Cancellation
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $order_cancellation->cancel_reason }}
{{ Form::open(['url'=>'order_cancellations/'.$order_cancellation->cancel_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/order_cancellations" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
