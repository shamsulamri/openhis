@extends('layouts.app')

@section('content')
<h1>Appointment List</h1>
<br>
<form action='/appointment/search' method='post' class='form-inline'>
	<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	{{ Form::select('services', $services, $service, ['class'=>'form-control']) }}
	<button class="btn btn-default" type="submit" value="Submit">Refresh</button>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
@if ($appointments->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Slot</th>
    <th>Service</th>
    <th>Patient</th>
    <th>Home Phone</th> 
    <th>Mobile Phone</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($appointments as $appointment)
	<tr>
			<td>
					<a href='{{ URL::to('appointment_services/'. $appointment->patient_id . '/0/'.$appointment->service_id. '/'.$appointment->appointment_id) }}'>
					{{ date('l, d F Y, H:i', strtotime($appointment->appointment_datetime)) }}
					</a>
			</td>
			<td>
					{{$appointment->service_name}}
			</td>
			<td>
					<a href='{{ URL::to('patients/'. $appointment->patient_id) }}'>
						{{$appointment->patient_name}}
					</a>
					<br>
					<small>{{$appointment->patient_mrn}}</small>
			</td>
			<td>
				{{ $appointment->patient_phone_home }}
			</td>
			<td>
				{{ $appointment->patient_phone_mobile }}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('appointments/delete/'. $appointment->appointment_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
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
@endsection
