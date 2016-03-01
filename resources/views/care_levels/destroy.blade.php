@extends('layouts.app')

@section('content')
<h1>
Delete Care Level
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $care_level->care_name }}
{{ Form::open(['url'=>'care_levels/'.$care_level->care_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/care_levels" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
