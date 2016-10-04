<div class='panel panel-default'>
<div class='panel-heading'>
	<h3 class='panel-title'>{{ $title }}</h3>
</div>
<div class='panel-body'>
@if (count($patients)>0)
<table class="table table-hover">
@foreach ($patients as $patient)
	<?php $status='' ?>
	@if ($admission->hasOpenConsultation($patient->patient_id, $patient->encounter_id)>0)
			<?php $status='warning' ?>
	@endif
	<tr class='{{ $status }}'>
			<td width='20%'>
					{{ date('d F Y', strtotime($patient->created_at)) }}
					<br>
					<small>
					<?php $ago =$dojo->diffForHumans($patient->created_at); ?> 
					{{ $ago }}
					</small>	
			</td>
			<td width='30%'>
					{{ strtoupper($patient->patient_name) }}
					@if ($patient->patient_birthdate)
					<br>
					<small>
					{{ $dojo->getAge($patient->patient_birthdate) }}, 
					{{ $patient->gender_name }}
					</small>
					@endif
					<br>
					<small>{{ $patient->patient_mrn }}</small>
			</td>
			<td>
					{{ $patient->bed_name }} 
					@if ($patient->room_name)
					/ {{ $patient->room_name }} 
					@endif
					<br>
					<small>{{ $patient->ward_name }}</small>
			</td>
			<td align='right'>
				@if ($admission->hasOpenConsultation($patient->patient_id, $patient->encounter_id)==0)
				<a class='btn btn-default btn-xs' href='{{ URL::to('consultations/create?encounter_id='. $patient->encounter_id) }}'>&nbsp;&nbsp;&nbsp;Start&nbsp;&nbsp;&nbsp;</a>
				@else
				<a class='btn btn-warning btn-xs' href='{{ URL::to('consultations/'. $admission->openConsultationId. '/edit') }}'>Resume</a>
				@endif
			</td>
	</tr>
@endforeach
</table>
@else
				<p>
				No case
				</p>
@endif
</div>
</div>
