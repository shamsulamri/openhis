@extends('layouts.app')

@section('content')
<h1>
Delete Maintenance Reason
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $maintenance_reason->reason_code }}
{{ Form::open(['url'=>'maintenance_reasons/'.$maintenance_reason->reason_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/maintenance_reasons" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
