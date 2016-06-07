<div class='well'>
		<h4>{{ $patient->getTitle() }} {{ $patient->patient_name }}</h4>
		<h6>{{ $patient->patient_mrn }}</h6>
		@if ($patient->outstandingBill() < 0) 
		<h6>
		<p class='text-danger'>
		<strong>Warning !</strong> Outstanding bill
		{{ $patient->outstandingBill() }}
		</p>
		</h6>
		@endif
</div>
