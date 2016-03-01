@extends('layouts.app')

@section('content')
<h1>
Delete Marital Status
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $marital_status->marital_name }}
{{ Form::open(['url'=>'marital_statuses/'.$marital_status->marital_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/marital_statuses" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
