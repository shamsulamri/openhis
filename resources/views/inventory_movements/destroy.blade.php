@extends('layouts.app')

@section('content')
<h1>
Delete Inventory Movement
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $inventory_movement->move_code }}
{{ Form::open(['url'=>'inventory_movements/'.$inventory_movement->move_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/inventory_movements" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
