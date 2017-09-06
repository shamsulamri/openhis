@extends('layouts.app')

@section('content')
<h1>
Delete Product Group
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $product_group->group_name }}
{{ Form::open(['url'=>'product_groups/'.$product_group->group_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/product_groups" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
