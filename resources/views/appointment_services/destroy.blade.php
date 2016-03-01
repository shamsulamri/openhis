@extends('layouts.app')

@section('content')
<h1>
Delete Appointment Service
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $appointment_service->service_name }}
{{ Form::open(['url'=>'appointment_services/'.$appointment_service->service_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/appointment_services" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
