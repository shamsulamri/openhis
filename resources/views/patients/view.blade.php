@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>Options</h1>
<br>
<h4>

				<a href='{{ URL::to('encounters/create?patient_id='. $patient->patient_id ) }}'>
					<span class='glyphicon glyphicon-flag' aria-hidden='true'></span>
						New Encounter
				</a>
				<br>
				<br>
				<a href='{{ URL::to('patients/'. $patient->patient_id) }}'>
					<span class='glyphicon glyphicon-user' aria-hidden='true'></span>
						Edit Demography		
				</a>
				<br>
				<br>
				<a href='{{ URL::to('appointment_services/'. $patient->patient_id . '/0') }}'>
					<span class='glyphicon glyphicon-calendar' aria-hidden='true'></span>
						Book Appointment
				</a>
				<br>
				<br>
				<a href='{{ URL::to('bed_bookings/create/'. $patient->patient_id) }}'>
					<span class='glyphicon glyphicon-bed' aria-hidden='true'></span>
						Book Bed
				</a>
				<br>
				<br>
				<a href='{{ URL::to('patients/dependants/'. $patient->patient_id) }}'>
					<span class='glyphicon glyphicon-heart' aria-hidden='true'></span>
						Define Dependants
				</a>
				<br>
				<br>
@if ($encounter)
				<a href='{{ URL::to('deposits/index/'. $encounter->encounter_id ) }}'>
					<span class='glyphicon glyphicon-usd' aria-hidden='true'></span>
						Deposit Collection						
				</a>
@else

					<span class='glyphicon glyphicon-usd' aria-hidden='true'></span>
						Deposit Collection						
@endif
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
