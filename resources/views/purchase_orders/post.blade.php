@extends('layouts.app2')

@section('content')
<h3>
Post Purchase Order
</h3>
@include('common.errors')
<br>
<h4>
Are you sure you want to post the selected purchase order ?
{{ Form::open(['url'=>'purchase_order/verify']) }}
<br>
<br>
	<a class="btn btn-default" href="/purchase_order_lines/index/{{ $purchase_order->purchase_id }}" role="button">Cancel</a>
	{{ Form::submit('Post Order', ['class'=>'btn btn-primary']) }}
	{{ Form::hidden('purchase_id', $purchase_order->purchase_id) }}
{{ Form::close() }}
</h4>
@endsection
