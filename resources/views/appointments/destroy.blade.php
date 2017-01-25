@extends('layouts.app')

@section('content')
<h1>
Delete Appointment
</h1>

<br>
{{ Form::open(['url'=>'appointments/'.$appointment->appointment_id, 'class'=>'form-horizontal']) }}
	{{ method_field('DELETE') }}
			<div class='form-group  @if ($errors->has('appointment_cancel')) has-error @endif'>
				<div class='col-sm-10'>
					<h3>
					Reason for cancellation
					</h3>
				</div>
			</div>
			<div class='form-group  @if ($errors->has('appointment_cancel')) has-error @endif'>
				<div class='col-sm-10'>
					{{ Form::textarea('appointment_cancel', null, ['class'=>'form-control','placeholder'=>'','rows'=>'4']) }}
				</div>
			</div>
			<div class='form-group'>
				<div class="col-sm-10">
					<a class="btn btn-default" href="/appointments" role="button">Cancel</a>
					{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
				</div>
			</div>
{{ Form::close() }}

@endsection
