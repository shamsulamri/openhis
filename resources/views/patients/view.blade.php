@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>Options</h1>
<br>
<h4>
					<span class='glyphicon glyphicon-flag' aria-hidden='true'></span>
					@if ($encounter_active)
								@if ($encounter->admission)
									Admitted at {{ $encounter->admission->bed->bed_name }} ({{ $encounter->admission->bed->ward->ward_name }})
								@else
									Queue at {{ $encounter->queue->location->location_name }}
								@endif
					@else
						<a href='{{ URL::to('encounters/create?patient_id='. $patient->patient_id ) }}'>
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
						Book Appointment
				</a>
				<br>
				<br>
					<span class='glyphicon glyphicon-bed' aria-hidden='true'></span>
				<a href='{{ URL::to('preadmissions/create/'. $patient->patient_id.'?book=preadmission') }}'>
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
@if ($encounter_active)
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
				<br>
				<br>
					<span class='glyphicon glyphicon-print' aria-hidden='true'></span>
				<a href='{{ Config::get('host.report_server') }}/ReportServlet?report=patient_label&id={{ $patient->patient_id }}'>
						{{ $patient->patient_Id }}Print Label
				</a>
</h4>
@endsection
