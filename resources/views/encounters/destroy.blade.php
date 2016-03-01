@extends('layouts.app')

@section('content')
<h1>
Delete Encounter
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $encounter->encounter_type }}
{{ Form::open(['url'=>'encounters/'.$encounter->encounter_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/encounters" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
