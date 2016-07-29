@extends('layouts.app')

@section('content')
@include('products.id')
<h1>
Delete Stock
</h1>
@include('common.errors')
<h3>
Are you sure you want to delete the selected record ?
{{ $stock->product_code }}
{{ Form::open(['url'=>'stocks/'.$stock->stock_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/stocks/{{ $product->product_code }}/{{ $stock->store_code }}" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
