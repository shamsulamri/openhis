@extends('layouts.app')

@section('content')
<h1>
Delete Urgency
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $urgency->urgency_name }}
{{ Form::open(['url'=>'urgencies/'.$urgency->urgency_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/urgencies" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
