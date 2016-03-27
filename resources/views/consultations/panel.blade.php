@if (!empty($consultation->encounter->discharge->discharge_id))
<div class='alert alert-danger' role='alert'>
<strong>Warning ! </strong>Editting discharged case.
</div>
@endif
<h2>Consultation</h2>
<br>
	<div class="row">
			<div class="col-xs-6">
            	<a class="btn btn-default" href="/consultations/progress/{{ $consultation->consultation_id }}" role="button">Progress Notes</a>
			</div>
			<div align="right" class="col-xs-6">
            	<a class="btn btn-warning" href="/consultations/close/{{ $consultation->consultation_id }}" role="button">Close Consultation</a>
	
				@if (!empty($consultation->encounter->discharge->discharge_id))
            		<a class="btn btn-success" href="/discharges/{{ $consultation->encounter->discharge->discharge_id }}/edit" role="button">Discharge Patient</a>
				@else
            		<a class="btn btn-success" href="/discharges/create/{{ $consultation->consultation_id }}" role="button">Discharge Patient</a>
				@endif
			</div>
	</div>

<br>
<ul class="nav nav-tabs nav-justified">
  <li role="presentation" class="
@if ($tab=='clinical')
	active
@endif
"><a href="/consultations/{{ $consultation->consultation_id }}/edit">Note</a></li>

  <li role="presentation" class="
@if ($tab=='diagnosis')
	active
@endif
"><a href="/consultation_diagnoses/{{ $consultation->consultation_id }}">Diagnosis</a></li>
<!--
  <li role="presentation" class="
@if ($tab=='procedure')
	active
@endif
"><a href="/consultation_procedures/{{ $consultation->consultation_id }}">Procedure</a></li>
-->
  <li role="presentation" class="
@if ($tab=='order')
	active
@endif
"><a href="/orders/{{ $consultation->consultation_id }}" role="button">Order</a></li>

<br>
