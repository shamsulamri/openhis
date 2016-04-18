@extends('layouts.app2')

@section('content')
<h1>
Delete Line Item
</h1>
<br>
@include('common.errors')
<h4>
Are you sure you want to delete the selected record ?
<br>
<br>
<strong>{{ $product->product_name }}</strong>
{{ Form::open(['url'=>'purchase_order_lines/'.$purchase_order_line->line_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<br>
	<br>
	<br>
	<a class="btn btn-default" href="/purchase_order_lines/index/{{ $purchase_order_line->purchase_id }}" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h4>
@endsection
