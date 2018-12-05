@extends('layouts.app')

@section('content')
<h1>
Delete Inventory
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $inventory->move_code }}
{{ Form::open(['url'=>'inventories/'.$inventory->inv_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/inventories" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
