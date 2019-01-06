@extends('layouts.app')

@section('content')
<h1>
Delete Purchase Request Status
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $purchase_request_status->status_name }}
{{ Form::open(['url'=>'purchase_request_statuses/'.$purchase_request_status->status_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/purchase_request_statuses" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
