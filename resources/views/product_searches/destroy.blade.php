@extends('layouts.app')

@section('content')
<h1>
Delete Product Search
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $product_search->product_name }}
{{ Form::open(['url'=>'product_searches/'.$product_search->product_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/product_searches" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
