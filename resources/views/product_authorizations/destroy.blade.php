@extends('layouts.app')

@section('content')
<h1>
Delete Product Authorization
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $product_authorization->author_id }}
{{ Form::open(['url'=>'product_authorizations/'.$product_authorization->id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/product_authorizations" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
