@extends('layouts.app')

@section('content')
<h1>
Delete Diet Quality
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $diet_quality->qc_date }}
{{ Form::open(['url'=>'diet_qualities/'.$diet_quality->qc_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/diet_qualities" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
