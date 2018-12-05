@extends('layouts.app')

@section('content')
@include('products.id')
<h1>
Delete Unit of Measure
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $product_uom->id }}
{{ Form::open(['url'=>'product_uoms/'.$product_uom->id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/product/uom/{{ $product->product_code }}" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
