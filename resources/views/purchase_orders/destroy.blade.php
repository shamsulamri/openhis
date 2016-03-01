@extends('layouts.app')

@section('content')
<h1>
Delete Purchase Order
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $purchase_order->purchase_date }}
{{ Form::open(['url'=>'purchase_orders/'.$purchase_order->purchase_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/purchase_orders" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
