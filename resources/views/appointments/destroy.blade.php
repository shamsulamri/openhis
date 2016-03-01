@extends('layouts.app')

@section('content')
<h1>
Delete Appointment
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $appointment->patient_id }}
{{ Form::open(['url'=>'appointments/'.$appointment->appointment_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/appointments" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
