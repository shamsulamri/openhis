@extends('layouts.app2')

@section('content')
<h3>
Delete Record
</h3>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $inventory->move_code }}
{{ Form::open(['url'=>'inventories/'.$inventory->inv_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/inventories/line/{{ $inventory->move_id }}" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
