@if (!empty($consultation->encounter->discharge->discharge_id))
<div class='alert alert-warning' role='warning'>
<strong>Warning ! </strong>Editting discharged case.
</div>
@endif
<div class='panel panel-default'>
	<div class='panel-heading'>
		<div class='row'>
			<div class='col-md-6'>
		<h4>{{ $patient->getTitle() }} {{ $patient->patient_name }}</h4>
		<h6>{{ $patient->patient_mrn }}</h6>
			</div>
			<div class='col-md-6'>
xxx
			</div>
		</div>
	</div>
	<div class='panel-body'>
@if (count($patient->alert)>0)
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
@endif
	</div>
</div>

