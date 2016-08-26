
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
<!--
<ul class='nav nav-pills nav-justified'>
			<li role='presentation' class=
				@if ($consultOption=='consultation')
					'active'
				@endif
			><a href="/consultations/{{ $consultation->consultation_id }}/edit"><span class='glyphicon glyphicon-comment' aria-hidden='true'></span> &nbsp;Consultation</a></li>
			<li role='presentation' class=
				@if ($consultOption=='medical_alerts')
					'active'
				@endif
			><a href="/medical_alerts"><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> &nbsp;Alerts</a></li>
			<li role='presentation' class=
				@if ($consultOption=='medical_certificate')
					'active'
				@endif
			><a href="/medical_certificates/create"><span class='glyphicon glyphicon-certificate' aria-hidden='true'></span> &nbsp;Certificate</a></li>
			@if ($consultation->encounter->admission)
			<li role='presentation' class=
				@if ($consultOption=='newborn')
					'active'
				@endif
			>
			<a href="/newborns?id={{ $consultation->consultation_id }}"><span class='glyphicon glyphicon-baby-formula' aria-hidden='true'></span> &nbsp;Newborn</a></li>
			<li role='presentation' class=
				@if ($consultOption=='dietary')
					'active'
				@endif
			><a href="/diet"><span class='glyphicon glyphicon-cutlery' aria-hidden='true'></span> &nbsp;Dietary</a></li>
			@endif
</ul>
-->
	</div>
</div>

