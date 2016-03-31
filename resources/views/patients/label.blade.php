@if (count($patient->alert)>0)
	<div class='alert alert-danger'>
	@foreach ($patient->alert as $alert)
		- {{ $alert->alert_description }}
		@if ($alert != end($patient->alert))
			<br>
		@endif
	@endforeach
	</div>
@endif
<div class='panel panel-primary'>
	<div class='panel-heading'>
	<div class="row">
			<div class="col-xs-6">
					<div class='form-group'>
						<div class='col-md-3'>
							Name
						</div>
						<div class='col-md-9'>
							{{ Form::label('patient_name', $patient->patient_name) }}
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group'>
						<div class='col-md-3'>
							MRN
						</div>
						<div class='col-md-9'>
							{{ Form::label('patient_mrn', $patient->patient_mrn) }}
						</div>
					</div>
			</div>
	</div>
	<div class="row">
			<div class="col-xs-6">
					<div class='form-group'>
						<div class='col-md-3'>
							Gender
						</div>
						<div class='col-md-9'>
							@if (!empty($patient->gender->gender_name))
							{{ Form::label('gender', $patient->gender->gender_name) }}
							@endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group'>
						<div class='col-md-3'>
							Identification
						</div>
						<div class='col-md-9'>
							{{ Form::label('identification', $patient->patientIdentification()) }}
						</div>
					</div>
			</div>
	</div>
	</div>
	<div class='panel-body'>
<ul class='nav nav-pills nav-justified'>
			<li role='presentation' class=
				@if ($consultOption=='consultation')
					'active'
				@endif
			><a href="/consultations/{{ $consultation->consultation_id }}/edit"><span class='glyphicon glyphicon-comment' aria-hidden='true'></span><br>Consultation</a></li>
			<li role='presentation' class=
				@if ($consultOption=='medical_alerts')
					'active'
				@endif
			><a href="/medical_alerts"><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span><br>Alerts</a></li>
			<li role='presentation' class=
				@if ($consultOption=='medical_certificate')
					'active'
				@endif
			><a href="/medical_certificates/create"><span class='glyphicon glyphicon-certificate' aria-hidden='true'></span><br>Certificate</a></li>
            <li role='presentation'><a href="#"><span class='glyphicon glyphicon-calendar' aria-hidden='true'></span><br>Appointment</a></li>
			<li role='presentation' class=
				@if ($consultOption=='newborn')
					'active'
				@endif
			><a href="/newborns?id={{ $consultation->consultation_id }}"><span class='glyphicon glyphicon-baby-formula' aria-hidden='true'></span><br>Newborn</a></li>
			<li role='presentation' class=
				@if ($consultOption=='dietary')
					'active'
				@endif
			><a href="/diet"><span class='glyphicon glyphicon-cutlery' aria-hidden='true'></span><br>Dietary</a></li>
			<li role="presentation" class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><span class='glyphicon glyphicon-bed' aria-hidden='true'></span><br>
					  Bed<span class="caret"></span>
					</a>
					<ul class="dropdown-menu">
							<li role='presentation'><a href="#">Bed Movement</a></li>
							<li role='presentation'><a href="#">Bed Bookings</a></li>
					</ul>
			</li>
</ul>
	</div>
</div>

@if (!empty($consultation->encounter->discharge->discharge_id))
<div class='alert alert-warning' role='warning'>
<strong>Warning ! </strong>Editting discharged case.
</div>
@endif
