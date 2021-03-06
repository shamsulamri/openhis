@extends('layouts.app')

@section('content')
<h1>
Delete Stock Limit
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $stock_limit->limit_id }}
{{ Form::open(['url'=>'stock_limits/'.$stock_limit->limit_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/stock_limits" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
