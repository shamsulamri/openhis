@extends('layouts.app')

@section('content')
<h1>
Delete Block Date
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $block_date->block_name }}
{{ Form::open(['url'=>'block_dates/'.$block_date->block_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/block_dates" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
