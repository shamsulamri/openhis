
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
		<div class='row'>
			<div class='col-md-10'>
				<h4>{{ $patient->getTitle() }} {{ $patient->patient_name }}</h4>
				<h6>{{ $patient->patient_mrn }}</h6>
				<h6>{{ $patient->patientAge() }}</h6>
			</div>
			<div class='col-md-2' align='right'>
			@if (Storage::disk('local')->has('/'.$patient->patient_mrn.'/'.$patient->patient_mrn))	
			<img id='show_image' src='{{ route('patient.image', ['id'=>$patient->patient_mrn]) }}' style='border:2px solid gray' height='90' width='75'>
			@else
					<img id='show_image' src='/profile-img.png' style='border:2px solid gray' height='85' width='75'>
			@endif
			</div>
		</div>
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
    <a class="btn btn-success" href="/consultations/progress/{{ $consultation->consultation_id }}" role="button">Progress</a>
</div>
<div class="btn-group" role="group" aria-label="...">
	<a href="/consultations/{{ $consultation->consultation_id }}/edit" class="btn btn-primary">Clinical Notes</a>
	<a href="/consultation_diagnoses" class="btn btn-primary">Diagnoses</a>
	<a href="/consultation_procedures" class="btn btn-primary">Procedures</a>
	<a href="/orders/make" class="btn btn-primary">Orders</a>
</div>


<div class="dropdown pull-right">
  <button class="btn btn-danger dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
    Discharge
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
	<li><a href="/medical_certificates/create">Medical Certificate</a></li>
	@if (empty($consultation->encounter->discharge->discharge_id))
    <li>
		<a href="/consultations/close">Close</a>
	</li>
    <li role="separator" class="divider"></li>
	<li>
			<a href="/discharges/create" role="button">Discharge</a>
	</li>
	@endif
  </ul>
</div>

<!--
<div class="dropdown pull-right">
  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
	<span class="glyphicon glyphicon-menu-hamburger"></span>
  </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
	<li><a href="/medical_alerts" title='Medical Alerts'>Medical Alerts</a></li>
	@if ($consultation->encounter->encounter_code=='inpatient')
	<li><a href="/diet">Dietary</a></li>
	<li><a href="/obstetric">Obstetric History</a></li>
	<li><a href="/newborns">Newborns</a></li>
	<li><a href="/documents?patient_mrn={{ $patient->patient_mrn }}">Documents</a></li>
	@endif
  </ul>
</div>
-->
<br>
<br>
