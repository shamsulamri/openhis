@extends('layouts.app')

@section('content')
<h1>
Delete Queue Location
</h1>

<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $queue_location->location_name }}
{{ Form::open(['url'=>'queue_locations/'.$queue_location->location_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/queue_locations" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
