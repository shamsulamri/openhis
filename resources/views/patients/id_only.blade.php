@if (!empty($patient))
<div class='well'>
		<div class='row'>
			<div class='col-md-10'>
						<h4>{{ $patient->getTitle() }} {{ $patient->patient_name }}</h4>
						<h6>{{ $patient->patient_mrn }}</h6>
						<h6>{{ $patient->patientAge() }}</h6>
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
			<div class='col-md-2' align='right'>
			@if (Storage::disk('local')->has('/'.$patient->patient_mrn.'/'.$patient->patient_mrn))	
			<img id='show_image' src='{{ route('patient.image', ['id'=>$patient->patient_mrn]) }}' style='border:2px solid gray' height='90' width='75'>
			@else
					<img id='show_image' src='/profile-img.png' style='border:2px solid gray' height='90' width='75'>
			@endif
			</div>
		</div>
</div>
@endif
