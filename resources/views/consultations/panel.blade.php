@include('patients.id_only')
<br>
@if (!empty($consultation->encounter->discharge->discharge_id))
<div class="row white-bg">
			<div class='col-sm-10'>
					<h3 class="text-danger"><i class="fa fa-warning"></i>&nbsp;Warning you are editting a discharged record.</h3>
			</div>
</div>
<br>
@endif

<div class="btn-group" role="group" aria-label="...">
		<div class="dropdown">
		  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
			<span class="glyphicon glyphicon-menu-hamburger"></span>
		  </button>
		  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
			@if ($consultation->encounter->encounter_code=='inpatient')
			<li><a href="{{ URL::to('diet') }}">Diet</a></li>
			@endif
			<li><a href='{{ URL::to('form/results',$consultation->encounter->encounter_id) }}'>Forms</a></li>
			<li><a href="/documents?patient_mrn={{ $patient->patient_mrn }}">Documents</a></li>
			<li><a href="{{ URL::to('medical_alerts') }}">Medical Alerts</a></li>
			@if ($patient->gender_code=='P')
			<li><a href="{{ URL::to('obstetric') }}">Obstetric History</a></li>
			<li><a href="{{ URL::to('newborns') }}">Newborn Registration</a></li>
			@endif
			@if ($consultation->encounter->encounter_code=='inpatient')
			<li><a href="{{ URL::to('admission/classification') }}">Patient Classification</a></li>
			@endif
			<li role="separator" class="divider"></li>
			<li><a href="{{ URL::to('medication_record/mar') }}">Medication Administration Record</a></li>
		  </ul>
		</div>
</div>

<div class="btn-group" role="group" aria-label="...">
	<a href="/consultations/{{ $consultation->consultation_id }}/edit" class="btn btn-success">Clinical Notes</a>
    <a class="btn btn-success" href="/consultations/progress2/{{ $consultation->consultation_id }}" role="button">Progress</a>
	<a href="/consultation_histories" class="btn btn-success">Histories</a>
	<!--
	<a href="/orders/make" class="btn btn-primary">Orders</a>
	<a href="/medications" class="btn btn-primary">Medications</a>
	-->



</div>

<div class="btn-group" role="group" aria-label="...">
	<!--
	<a href="/orders/procedure" class="btn btn-danger">Procedures</a>
	-->
	<a href="/consultation_diagnoses" class="btn btn-primary">Diagnoses</a>
	<a href="/consultation_procedures" class="btn btn-primary">Procedures</a>
	<a href="/orders/medication" class="btn btn-primary">Medications</a>
	<a href="/orders/make" class="btn btn-primary">Orders</a>
</div>

<div class="dropdown pull-right">
  <button class="btn btn-danger dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
    Close
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
	<li><a href="/consultations/close">
	@if ($consultation->encounter->encounter_code == 'outpatient')
		Suspend	Consultation
	@else
		End Consultation
	@endif
	</a></li>
			<li role="separator" class="divider"></li>
			<li><a href="/medical_certificates/create">Medical Certificate</a></li>
	@if (empty($consultation->encounter->discharge->discharge_id))
			@can('discharge_patient')
			<li><a href="/discharges/create" role="button">Discharge</a></li>
			@endcan
	@endif
  </ul>
</div>
<br>
