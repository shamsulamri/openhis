<div class='well'>
<h3>{{ $consultation->encounter->patient->patient_name }}</h3>
<h5>{{ $consultation->encounter->patient->patient_mrn }}</h5>
</div>

@if (!is_null($consultation->encounter->discharge->discharge_id))
<div class='alert alert-danger' role='alert'>
<strong>Warning ! </strong>Editting discharged case.
</div>
@endif
