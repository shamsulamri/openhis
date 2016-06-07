@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>Options</h1>
<br>
<h4>
					@if ($encounter_active==1)
							<span class='glyphicon glyphicon-flag' aria-hidden='true'></span>
								@if ($encounter->admission)
									Patient admitted at {{ $encounter->admission->bed->bed_name }} ({{ $encounter->admission->bed->ward->ward_name }})
								@else
									Patient queue at {{ $encounter->queue->location->location_name }}
								@endif
					@else
						<a href='{{ URL::to('encounters/create?patient_id='. $patient->patient_id ) }}'>
							<span class='glyphicon glyphicon-flag' aria-hidden='true'></span>
								New Encounter
						</a>
					@endif
				<br>
				<br>
					<span class='glyphicon glyphicon-user' aria-hidden='true'></span>
				<a href='{{ URL::to('patients/'. $patient->patient_id).'/edit' }}'>
						Edit Patient 
				</a>
				<br>
				<br>
					<span class='glyphicon glyphicon-calendar' aria-hidden='true'></span>
				<a href='{{ URL::to('appointment_services/'. $patient->patient_id . '/0') }}'>
						Set Appointment
				</a>
				<br>
				<br>
					<span class='glyphicon glyphicon-bed' aria-hidden='true'></span>
				<a href='{{ URL::to('bed_bookings/create/'. $patient->patient_id.'?book=preadmission') }}'>
						Preadmission 
				</a>
				<br>
				<br>
					<span class='glyphicon glyphicon-heart' aria-hidden='true'></span>
				<a href='{{ URL::to('patients/dependant_list/'. $patient->patient_id) }}'>
						Dependant List
				</a>
				<br>
				<br>
@if ($encounter_active==1)
					<span class='glyphicon glyphicon-usd' aria-hidden='true'></span>
				<a href='{{ URL::to('deposits/index/'. $encounter->encounter_id ) }}'>
						Deposit Collection						
				</a>
@else
					<span class='glyphicon glyphicon-usd' aria-hidden='true'></span>
						Deposit Collection						
@endif
				<br>
				<br>
					<span class='glyphicon glyphicon-book' aria-hidden='true'></span>
				<a href='{{ URL::to('payments/'. $patient->patient_id) }}'>
						Payment Collection
				</a>
</h4>
<!--
@if (count($patients)>0)
<h1>Dependants</h1>

<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>MRN</th> 
    <th>Home Phone</th> 
    <th>Mobile Phone</th> 
    <th></th> 
	</tr>
  </thead>
	<tbody>
@foreach ($patients as $p)
	<tr>
		<td>
			{{ $p->patient_name }}
		</td>
		<td>
			{{ $p->patient_mrn }}
		</td>
		<td>
			{{ $p->patient_phone_home }}
		</td>
		<td>
			{{ $p->patient_phone_mobile }}
		</td>
		<td>
			<a class='btn btn-default btn-xs pull-right' href='/patients/{{ $p->patient_id }}'>Select</a>
		</td>
	</tr>
@endforeach
</tbody>
</table>
@endif
-->
@endsection
