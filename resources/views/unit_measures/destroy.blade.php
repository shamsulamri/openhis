@extends('layouts.app')

@section('content')
<h1>
Delete Unit Measure
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $unit_measure->unit_name }}
{{ Form::open(['url'=>'unit_measures/'.$unit_measure->unit_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/unit_measures" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
