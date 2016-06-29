
@if (!empty($consultation->encounter->discharge->discharge_id))
<div class='alert alert-warning' role='warning'>
<strong>Warning ! </strong>Editting discharged case.
</div>
@endif
@if (count($patient->alert)>0)
<div class='panel panel-default'>
	<div class='panel-heading'>
@else
	<div class='well'>
@endif
		<h4>{{ $patient->getTitle() }} {{ $patient->patient_name }}</h4>
		<h6>{{ $patient->patient_mrn }}</h6>
	</div>
@if (count($patient->alert)>0)
	<div class='panel-body'>
	<h4 class='text-danger'>
	<strong>
	@foreach ($patient->alert as $alert)
		{{ $alert->alert_description }}
		@if ($alert != end($patient->alert))
			<br>
		@endif
	@endforeach
	</strong>
	</h4>
	</div>
@endif
@if (count($patient->alert)>0)
</div>
@endif

<div class="btn-group" role="group" aria-label="...">
    <a class="btn btn-success" href="/consultations/progress/{{ $consultation->consultation_id }}" role="button">Progress Notes</a>
</div>
<div class="btn-group" role="group" aria-label="...">
	<a href="/consultations/{{ $consultation->consultation_id }}/edit" class="btn btn-primary">Clincal Notes</a>
	<a href="/consultation_diagnoses" class="btn btn-primary">Diagnoses</a>
	<a href="/consultation_procedures" class="btn btn-primary">Procedures</a>
	<a href="/orders/make" class="btn btn-primary">Orders</a>
</div>
<div class="btn-group" role="group" aria-label="...">
	<a href="/medical_alerts" class="btn btn-default" title='Medical Alerts'>Medical Alerts</a>
	@if ($consultation->encounter->encounter_code=='inpatient')
	<a href="/diet" class='btn btn-default'>Dietary</a>
	@endif
	<a href="/medical_certificates/create" class='btn btn-default'>Medical Certificate</a>
</div>

<div class="dropdown pull-right">
  <button class="btn btn-danger dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
    Discharge
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
    <li>
		<a href="/consultations/close">Close</a>
	</li>
    <li role="separator" class="divider"></li>
	<li>
		@if (!empty($consultation->encounter->discharge->discharge_id))
			<a href="/discharges/{{ $consultation->encounter->discharge->discharge_id }}/edit" role="button">Discharge</a>
		@else
			<a href="/discharges/create" role="button">Clincal Discharge</a>
		@endif
	</li>
  </ul>
</div>
<br>
