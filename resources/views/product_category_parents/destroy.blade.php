@extends('layouts.app')

@section('content')
<h1>
Delete Product Category Parent
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $product_category_parent->parent_code }}
{{ Form::open(['url'=>'product_category_parents/'.$product_category_parent->parent_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/product_category_parents" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
