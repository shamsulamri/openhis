@extends('layouts.app')

@section('content')
<h1>
Delete Encounter Type
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $encounter_type->encounter_name }}
{{ Form::open(['url'=>'encounter_types/'.$encounter_type->encounter_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/encounter_types" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
