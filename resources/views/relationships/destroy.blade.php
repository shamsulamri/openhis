@extends('layouts.app')

@section('content')
<h1>
Delete Relationship
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $relationship->relation_name }}
{{ Form::open(['url'=>'relationships/'.$relationship->relation_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/relationships" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
