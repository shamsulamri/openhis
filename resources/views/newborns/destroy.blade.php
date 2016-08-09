@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>
Delete Newborn
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $newborn->encounter_id }}
{{ Form::open(['url'=>'newborns/'.$newborn->newborn_id, 'class'=>'']) }}
	{{ method_field('DELETE') }}
	<br>
	<a class="btn btn-default" href="/newborns" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
