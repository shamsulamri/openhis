@extends('layouts.app')

@section('content')
<h1>
Delete Stock Movement
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $stock_movement->move_name }}
{{ Form::open(['url'=>'stock_movements/'.$stock_movement->move_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/stock_movements" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
