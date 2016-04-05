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
				<a href='{{ URL::to('patients/'. $patient->patient_id . '/edit?tab=demography') }}'>
					<span class='glyphicon glyphicon-calendar' aria-hidden='true'></span><br>
						Give<br>Appointment
				</a>
			</li>
			<li role='presentation'
				@if ($patientOption=='bed')
					class='active'
				@endif
			>
				<a href='{{ URL::to('bed_bookings/create/'. $patient->patient_id) }}'>
					<span class='glyphicon glyphicon-bed' aria-hidden='true'></span><br>
						Bed<br>Booking
				</a>
			</li>
			<li role='presentation'
				@if ($patientOption=='dependants')
					class='active'
				@endif
			>
				<a href='{{ URL::to('patients/'. $patient->patient_id . '/edit?tab=demography') }}'>
					<span class='glyphicon glyphicon-heart' aria-hidden='true'></span><br>
						Define<br>Dependants
				</a>
			</li>
			<li role='presentation'
				@if ($patientOption=='print')
					class='active'
				@endif
			>
				<a href='{{ URL::to('patients/'. $patient->patient_id . '/edit?tab=demography') }}'>
					<span class='glyphicon glyphicon-print' aria-hidden='true'></span><br>
						Print Forms & Labels
				</a>
			</li>
		</ul>
	</div>
</div>
@endsection
