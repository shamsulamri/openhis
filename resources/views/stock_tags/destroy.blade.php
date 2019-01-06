@extends('layouts.app')

@section('content')
<h1>
Delete Stock Tag
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $stock_tag->tag_name }}
{{ Form::open(['url'=>'stock_tags/'.$stock_tag->tag_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/stock_tags" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
