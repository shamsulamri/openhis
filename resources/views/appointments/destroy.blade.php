@extends('layouts.app')

@section('content')
@include('patients.id_only')
<h1>
Delete Appointment
</h1>

<br>
{{ Form::open(['url'=>'appointments/'.$appointment->appointment_id, 'class'=>'form-horizontal']) }}
	{{ method_field('DELETE') }}
			<div class='form-group  @if ($errors->has('appointment_cancel')) has-error @endif'>
				<div class='col-sm-10'>
						<h4>
						Appointment slot on {{ date('l d F, h:i a', strtotime($appointment->appointment_datetime )) }}
						</h4>
						<br>
						Reason for cancellation
				</div>
			</div>
			<div class='form-group  @if ($errors->has('appointment_cancel')) has-error @endif'>
				<div class='col-sm-12'>
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
