@extends('layouts.app')

@section('content')
<h1>
Delete Product
</h1>
<h3>
Are you sure you want to delete the selected record ?
<br>
<br>
{{ $product->product_name }}
<br>
<br>
{{ Form::open(['url'=>'products/'.$product->product_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/products" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
