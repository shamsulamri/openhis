@if (!empty($patient))
<div class="row border-bottom gray-bg">
			<div class='col-sm-10'>
						<h2>{{ $patient->getTitleName() }}</h2>
						<h6>{{ $patient->getMRN() }}</h6>
						<h6>{{ $patient->patientAge() }}</h6>
		@if (!empty($encounter))
			@if ($encounter->admission) 
						<h4>
							{{ $encounter->admission->bed->bed_name }}
							({{ $encounter->admission->bed->ward->ward_name }})
						</h4>
			@endif
		@endif
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
					<h2>
					@if (Storage::disk('local')->has('/'.$patient->patient_mrn.'/'.$patient->patient_mrn))	
					<img id='current_image' src='{{ route('patient.image', ['id'=>$patient->patient_mrn]) }}' style='border:2px solid gray' height='80' width='70'>
					@else
							<img id='current_image' src='/profile-img.png' style='border:2px solid gray' height='80' width='70'>
					@endif
					</h2>
			</div>
			&nbsp;
</div>
@endif
