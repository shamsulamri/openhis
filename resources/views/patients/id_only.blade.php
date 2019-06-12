<div class="row border-bottom gray-bg">
			<div class='col-sm-10'>
						<h2>{{ $patient->getTitleName() }}</h2>
						<h5>{{ $patient->patientAge() }}</h5>
						<h5>{{ $patient->getMRN() }} 
	@if (!empty($consultation))
		@if ($consultation->encounter->sponsor)
			({{ $consultation->encounter->sponsor->sponsor_name }})
		@else
			(Full paying patient)
		@endif
	@endif


						</h5>
						<h5>{{ $patient->getCurrentAddress() }}</h5>
@if ($patient->patient_gravida)
						<h5>
							G{{ $patient->patient_gravida }}
							P{{ $patient->patient_parity }}{{ $patient->patient_parity_plus?'+'.$patient->patient_parity_plus:'' }}
{{ $patient->patient_lnmp?'LNMP: '.$patient->patient_lnmp:'' }}
								
						</h5>
@endif
		@if (!empty($consultation->encounter))
			@if ($consultation->encounter->admission) 
						<h3>
							{{ $consultation->encounter->admission->bed->bed_name }}
							({{ $consultation->encounter->admission->bed->ward->ward_name }})
						</h3>
			@endif
		@endif

			</div>
			<div class='col-md-2' align='right'>
					<h2>
					@if (Storage::disk('local')->has('/mykad_photos/'.$patient->patient_id))	
					<img id='current_image' src='{{ route('patient.image', ['id'=>$patient->patient_id]) }}' style='border:2px solid gray' height='110' width=auto>
					@else
							<img id='current_image' src='/profile-img.png' style='border:2px solid gray' height='110' width=auto>
					@endif
					</h2>
			</div>
</div>
<?php
	$alerts = $patient->alert;
?>
@if (count($alerts)>0)
	<br>
	<div class='alert alert-warning' role='alert'>
	<p>
	@foreach ($alerts as $alert)
		@if ($alert->alert_public==1) 
		- {{ $alert->alert_description }}
		@if ($alert != end($patient->alert))
			<br>
		@endif
		@endif
	@endforeach
	</p>
	</div>
@else
@endif
