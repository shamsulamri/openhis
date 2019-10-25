@extends('layouts.app')

@section('content')
<h1>
Delete Order
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $order->product_code }}
{{ Form::open(['url'=>'order_maintenances/'.$order->order_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/order_maintenances" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
