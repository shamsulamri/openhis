@extends('layouts.app')

@section('content')
@include('patients.id_only')

<h1>Admission Options</h1>
<br>
<h4>
		@if (is_null($admission->arrival) && empty($admission->discharge_id))
			<span class='fa fa-sign-in' aria-hidden='true'></span>
			<a href='{{ URL::to('ward_arrivals/create/'. $admission->encounter_id) }}'>Log Arrival</a>
		@endif

		@if ($admission->arrival) 
				@if (!empty($admission->encounter->discharge))
						<span class='fa fa-sign-out' aria-hidden='true'></span>
						<a href='{{ URL::to('ward_discharges/create/'. $admission->admission_id) }}'>Ward Discharge</a>
						<br><br>
						<span class='fa fa-calendar' aria-hidden='true'></span>
						<a href='{{ URL::to('appointment_services/'. $patient->patient_id . '/0') }}'>Appointment</a>
				@else
						@if (!is_null($admission->arrival)) 
							@if ($admission->bed->ward->ward_code != 'mortuary')
								<span class='fa fa-table' aria-hidden='true'></span>
								<a href='{{ URL::to('medication_record/mar/'. $admission->encounter_id) }}'>
									Medication Administration Record
								</a>
								<br><br>
						
								<span class='glyphicon glyphicon-bed' aria-hidden='true'></span>
								<a href='{{ URL::to('admission_beds?flag=1&admission_id='. $admission->admission_id) }}' title='Bed movement'>
									Bed Movement
								</a>
								<br><br>
							@endif
						<span class='fa fa-wpforms' aria-hidden='true'></span>
						<a href='{{ URL::to('form/results/'. $admission->encounter_id) }}'>
							Forms
						</a>

						<br><br>
						<span class='fa fa-print' aria-hidden='true'></span>
						<a target="_blank" href="{{ Config::get('host.report_server') }}/ReportServlet?report=patient_label&id={{ $admission->patient_id }}">
								Patient Label
						</a>

						<br><br>
						<span class='fa fa-print' aria-hidden='true'></span>
						<a target="_blank" href="{{ Config::get('host.report_server')  }}/ReportServlet?report=wrist_label&id={{ $admission->patient_id }}">
								Wrist Label
						</a>

						<?php
						$consultation = $wardHelper->hasOpenConsultation($admission->encounter->patient_id, $admission->encounter_id, Auth::user()->id);
						?>

						@endif
				@endif

				<br><br>
				@if (empty($consultation))
				<span class='fa fa-stethoscope' aria-hidden='true'></span>
				<a href='{{ URL::to('admission/consultation/'. $admission->admission_id) }}'>Start Consultation</a>
				@else
				<span class='fa fa-stethoscope' aria-hidden='true'></span>
				<a href='{{ URL::to('consultations/'. $wardHelper->openConsultationId. '/edit') }}'>Resume Consultation</a>
				@endif
		@endif
</h4>
@endsection
