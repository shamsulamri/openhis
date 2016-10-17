@extends('layouts.app')

@section('content')
@include('patients.id')

<h1>Options</h1>
<br>
<h4>
		@if (is_null($admission->encounter->discharge))
		<span class='glyphicon glyphicon-user' aria-hidden='true'></span>
		<a href='{{ URL::to('admissions/'. $admission->admission_id . '/edit') }}'>Edit Admission</a>
		</a>
		<br>
		<br>
		@endif

		@if (is_null($admission->encounter->wardArrival)) 
		<span class='glyphicon glyphicon-bed' aria-hidden='true'></span>
		<a href='{{ URL::to('ward_arrivals/create/'. $admission->encounter_id) }}'>Log Arrival</a>
		</a>
		<br>
		<br>
		@elseif (!is_null($admission->encounter->discharge))
		<span class='glyphicon glyphicon-bed' aria-hidden='true'></span>
		<a href='{{ URL::to('ward_discharges/create/'. $admission->admission_id) }}'>Ward Discharge</a>
		</a>
		<br>
		<br>
		@else

		<span class='glyphicon glyphicon-folder-close' aria-hidden='true'></span>
		<a href="{{ URL::to('loans/request/'. $admission->patient_mrn.'?type=folder') }}">Folder Request</a>
		</a>
		<br>
		<br>

		<span class='glyphicon glyphicon-resize-horizontal' aria-hidden='true'></span>
		<a href='{{ URL::to('admission_beds?flag=1&admission_id='. $admission->admission_id) }}'>Bed Movement</a>
		</a>
		<br>
		<br>

		<!--
		<span class='glyphicon glyphicon-bed' aria-hidden='true'></span>
		<a href='{{ URL::to('bed_bookings/create/'. $patient->patient_id.'/'.$admission->admission_id) }}'>Bed Booking</a>
		</a>
		<br>
		<br>
		-->

		@endif


</h4>
@endsection
