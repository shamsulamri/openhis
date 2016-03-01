@extends('layouts.app')

@section('content')
<h1>
Delete Bed Status
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $bed_status->status_name }}
{{ Form::open(['url'=>'bed_statuses/'.$bed_status->status_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/bed_statuses" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
