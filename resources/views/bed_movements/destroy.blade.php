@extends('layouts.app')

@section('content')
<h1>
Delete Bed Movement
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $bed_movement->admission_id }}
{{ Form::open(['url'=>'bed_movements/'.$bed_movement->move_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/bed_movements" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
