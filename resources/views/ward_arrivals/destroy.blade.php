@extends('layouts.app')

@section('content')
<h1>
Delete Ward Arrival
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $ward_arrival->arrival_description }}
{{ Form::open(['url'=>'ward_arrivals/'.$ward_arrival->arrival_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/ward_arrivals" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
