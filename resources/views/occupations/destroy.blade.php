@extends('layouts.app')

@section('content')
<h1>
Delete Occupation
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $occupation->occupation_name }}
{{ Form::open(['url'=>'occupations/'.$occupation->occupation_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/occupations" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
