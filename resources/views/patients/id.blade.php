<div class='panel panel-default'>
	<div class='panel-heading'>
		<h4>{{ $patient->getTitle() }} {{ $patient->patient_name }}</h4>
		<h6>{{ $patient->patient_mrn }}</h6>
		<h6>
		<p class='text-danger'>
		<strong>Warning !</strong> Outstanding bill
		</p>
		</h6>
	</div>
</div>
