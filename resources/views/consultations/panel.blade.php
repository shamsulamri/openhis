<div class="row border-bottom gray-bg">
			<div class='col-sm-10'>
						<h2>{{ $patient->getTitle() }} {{ $patient->patient_name }}</h2>
						<h6>{{ $patient->getMRN() }}</h6>
						<h6>{{ $patient->patientAge() }}</h6>
			</div>
			<div class='col-md-2' align='right'>
					<h2>
					@if (Storage::disk('local')->has('/'.$patient->patient_mrn.'/'.$patient->patient_mrn))	
					<img id='current_image' src='{{ route('patient.image', ['id'=>$patient->patient_mrn]) }}' style='border:2px solid gray' height='80' width='70'>
					@else
							<img id='current_image' src='/profile-img.png' style='border:2px solid gray' height='80' width='70'>
					@endif
					</h2>
			</div>
</div>

@if (!empty($consultation->encounter->discharge->discharge_id))
<div class="row white-bg">
			<br>
			<div class='col-sm-10'>
					<h3 class="text-danger"><i class="fa fa-warning"></i>Warning you are editting discharged cases.</h3>
			</div>
</div>
@endif
@if (count($patient->alert)>0)
	<br>
	<div class='alert alert-danger' role='alert'>
	<p>
	@foreach ($patient->alert as $alert)
		- {{ $alert->alert_description }}
		@if ($alert != end($patient->alert))
			<br>
		@endif
	@endforeach
	</p>
	</div>
@else
	<br>
@endif

<div class="btn-group" role="group" aria-label="...">
		<div class="dropdown">
		  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
			<span class="glyphicon glyphicon-menu-hamburger"></span>
		  </button>
		  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
			<li><a href="{{ URL::to('medication_record/mar') }}">Medication Administration Record</a></li>
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
		  </ul>
		</div>
</div>

<div class="btn-group" role="group" aria-label="...">
    <a class="btn btn-success" href="/consultations/progress/{{ $consultation->consultation_id }}" role="button">Progress</a>
	<a href="/consultations/{{ $consultation->consultation_id }}/edit" class="btn btn-primary">Clinical Notes</a>
	<a href="/consultation_diagnoses" class="btn btn-primary">Diagnoses</a>
	<a href="/consultation_procedures" class="btn btn-primary">Procedures</a>
	<a href="/orders/make" class="btn btn-primary">Orders</a>
</div>




<div class="dropdown pull-right">
  <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
    Close
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
	<li><a href="/consultations/close">End Consultation</a></li>
	@if (empty($consultation->encounter->discharge->discharge_id))
			@if (Auth::user()->consultant)
			<li role="separator" class="divider"></li>
			<li><a href="/medical_certificates/create">Medical Certificate</a></li>
			<li><a href="/discharges/create" role="button">Clinical Discharge</a></li>
			@endif
	@endif
  </ul>
</div>
<br>
