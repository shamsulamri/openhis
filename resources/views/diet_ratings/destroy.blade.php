@extends('layouts.app')

@section('content')
<h1>
Delete Diet Rating
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $diet_rating->rate_name }}
{{ Form::open(['url'=>'diet_ratings/'.$diet_rating->rate_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/diet_ratings" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
