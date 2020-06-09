@extends('layouts.app')

@section('content')
<h1>Appointment List</h1>
@if (!empty($service))
<h3>{{ $service->service_name }}</h3>
@endif
<form action='/appointment/search' method='post' class='form-inline'>
	<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>

	@if (!$service)
	{{ Form::select('services', $services, $service_id, ['class'=>'form-control']) }}
	@else
	{{ Form::hidden('services', Auth::user()->service_id) }}
	@endif
	<div class="input-group date">
		<input data-mask="99/99/9999" name="date_start" id="date_start" type="text" class="form-control" value="{{ $date_start }}">
		<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
	</div>
	<button class="btn btn-primary" type="submit" value="Submit">Refresh</button>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if ($appointments->total()>0)
<form action='/appointments/multiple_delete' method='post'>
<table class="table table-hover">
 <thead>
	<tr> 
    <th width='10'></th>
    <th>Slot</th>
    <th>Patient</th>
    <th>Service</th>
    <th>Home Phone</th> 
    <th>Mobile Phone</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($appointments as $appointment)
	<?php
		$current_encounter = $appointment->patient->activeEncounter();
	?>
	<tr>
			<td>
					{{ Form::checkbox($appointment->appointment_id, 1, null) }}
			</td>
			<td>
					<?php
						$week = DojoUtility::weekOfMonth($appointment->appointment_datetime)-1;
					?>
					<a href='{{ URL::to('appointment_services/'. $appointment->patient_id . '/'.$week.'/'.$appointment->service_id. '/'.$appointment->appointment_id) }}?edit=true'>
					{{ DojoUtility::dateLongFormat($appointment->appointment_datetime) }}
					</a>
			</td>
			<td>
					<a href='{{ URL::to('patients/'. $appointment->patient_id.'/edit') }}'>
						{{$appointment->patient->patient_name}}
					</a>
					<br>
					<small>{{$appointment->patient->patient_mrn}}</small>
			</td>
			<td>
					{{ strtoupper($appointment->service->service_name) }}
			</td>
			<td>
				{{ $appointment->patient->patient_phone_home }}
			</td>
			<td>
				{{ $appointment->patient->patient_phone_mobile }}
			</td>
			<td align='right'>
				@if (!isset($current_encounter)) 
					@can('module-patient')
					<a class='btn btn-danger btn-sm' href='{{ URL::to('appointments/delete/'. $appointment->appointment_id) }}'>Delete</a>
					@endcan
					<a class='btn btn-primary btn-sm' data-toggle="tooltip" data-placement="top" title="Start Encounter" href='{{ URL::to('encounters/create?patient_id='. $appointment->patient_id.'&appointment_id='.$appointment->appointment_id) }}'>
						<i class="fa fa-flag"></i>
					</a>
				@else
					@if ($current_encounter->discharge) 
						Discharge
					@else 
						In Queue
					@endif
				@endif
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
			@if ($appointments->total()>0)
			{{ Form::submit('Delete Selection', ['class'=>'btn btn-warning btn-xs']) }}
			<input type='hidden' name="_token" value="{{ csrf_token() }}">
			@endif
			<!--
			<input type='hidden' name="_ids" value="{{ $appointments->pluck('appointment_id') }}">
			-->
</form>
@if (isset($search)) 
	{{ $appointments->appends(['search'=>$search])->render() }}
	@else
	{{ $appointments->render() }}
@endif
<br>
@if ($appointments->total()>0)
	{{ $appointments->total() }} records found.
@else
	No record found.
@endif
	<script>
		$('#date_start').datepicker({
				format: "dd/mm/yyyy",
				todayBtn: "linked",
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true
		});
	</script>
@endsection
