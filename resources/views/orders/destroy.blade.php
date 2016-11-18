@extends('layouts.app2')

@section('content')
<h3>
Delete Order
</h3>

{{ Form::open(['url'=>'orders/'.$order->order_id]) }}
<p>
Are you sure you want to delete the selected record ?
</p>
<h4>
{{ $order->product->product_name }}
</h4>
<br>
{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/orders" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

@endsection
