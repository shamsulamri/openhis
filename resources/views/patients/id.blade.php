	<div class='well'>
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
<div class='alert alert-danger' role='alert'>
<strong>Warning !</strong> Outstanding bill
</div>
