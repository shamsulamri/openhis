@if (!empty($patient))
<div class='well'>
		<h4>{{ $patient->getTitle() }} {{ $patient->patient_name }}</h4>
		<h6>{{ $patient->patient_mrn }}</h6>
		@if ($patient->outstandingBill() < 0) 
		<h4>
		<p class='text-warning'>
		<strong>Warning !</strong> Outstanding bill
		</p>
		</h4>
		@endif
		@if (!empty($encounter->discharge))
				@if (!$encounter->encounterPaid())
				<h4 class='text-danger'>
					<strong>
					Encounter not paid
					</strong>	
				</h4>
				@endif
		@endif
</div>
@endif
