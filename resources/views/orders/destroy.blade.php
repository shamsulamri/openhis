@extends('layouts.app2')

@section('content')
<h2>
Delete Order
</h2>
@include('common.errors')
<br>
<h4>
{{ Form::open(['url'=>'orders/'.$order->order_id]) }}
Are you sure you want to delete the selected record ?
<br>
<br>
{{ $order->product->product_name }}
<br>
<br>
{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/orders" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h4>
@endsection
