<div class='well'>
		<h4>{{ $patient->getTitle() }} {{ $patient->patient_name }}</h4>
		<h6>{{ $patient->patient_mrn }}</h6>
		@if ($patient->outstandingBill() < 0) 
		<h4>
		<p class='text-danger'>
		<strong>Warning !</strong> Outstanding bill
		{{ $patient->outstandingBill() }}
		</p>
		</h4>
		@endif
		@if (isset($encounter))
			@if ($encounter->encounter_code=='outpatient' || $encounter->encounter_code=='emergency')
				@if ($encounter->encounterPaid()==0)
				<h4 class='text-danger'>
					<strong>
					Encounter not paid
					</strong>	
				</h4>
				@endif
			@endif
		@endif
</div>
