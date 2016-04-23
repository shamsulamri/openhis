@extends('layouts.app')

@section('content')
@include('patients.id')

<div class='panel panel-primary'>
	<div class='panel-heading'>
		Options
	</div>
	<div class='panel-body'>
		<ul class='nav nav-pills nav-justified'>
			<li role='presentation'
				@if ($patientOption=='encounter')
					class='active'
				@endif
			>
				<a href='{{ URL::to('encounters/create?patient_id='. $patient->patient_id ) }}'>
					<span class='glyphicon glyphicon-flag' aria-hidden='true'></span><br>
						New<br>Encounter
				</a>
			</li>
			<li role='presentation'
				@if ($patientOption=='demography')
					class='active'
				@endif
			>
				<a href='{{ URL::to('patients/'. $patient->patient_id . '/edit?tab=demography') }}'>
					<span class='glyphicon glyphicon-user' aria-hidden='true'></span><br>
						Edit<br>Demography		
				</a>
			</li>
			<li role='presentation'
				@if ($patientOption=='appointment')
					class='active'
				@endif
			>
				<a href='{{ URL::to('appointment_services/'. $patient->patient_id . '/0') }}'>
					<span class='glyphicon glyphicon-calendar' aria-hidden='true'></span><br>
						Book<br>Appointment
				</a>
			</li>
			<li role='presentation'
				@if ($patientOption=='bed')
					class='active'
				@endif
			>
				<a href='{{ URL::to('bed_bookings/create/'. $patient->patient_id) }}'>
					<span class='glyphicon glyphicon-bed' aria-hidden='true'></span><br>
						Book<br>Bed
				</a>
			</li>
			<li role='presentation'
				@if ($patientOption=='dependants')
					class='active'
				@endif
			>
				<a href='{{ URL::to('patients/dependants/'. $patient->patient_id . '?tab=dependants') }}'>
					<span class='glyphicon glyphicon-heart' aria-hidden='true'></span><br>
						Define<br>Dependants
				</a>
			</li>
		</ul>
	</div>
</div>
<h2>Dependants</h2>
<br>
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

@endsection
