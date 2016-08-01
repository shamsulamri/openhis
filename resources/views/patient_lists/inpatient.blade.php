<div class='panel panel-default'>
<div class='panel-heading'>
	<h3 class='panel-title'>Inpatient</h3>
</div>
<div class='panel-body'>
@if (count($inpatients)>0)
<table class="table table-hover">
@foreach ($inpatients as $inpatient)
	<?php $status='' ?>
	@if ($admission->hasOpenConsultation($inpatient->patient_id, $inpatient->encounter_id)>0)
			<?php $status='warning' ?>
	@endif
	<tr class='{{ $status }}'>
			<td width='20%'>
					{{ date('d F Y', strtotime($inpatient->created_at)) }}
					<br>
					<small>
					<?php $ago =$dojo->diffForHumans($inpatient->created_at); ?> 
					{{ $ago }}
					</small>	
			</td>
			<td width='30%'>
					{{ strtoupper($inpatient->patient_name) }}
					<br>
					<small>{{ $inpatient->patient_mrn }}</small>
			</td>
			<td>
					{{ $inpatient->bed_name }} / {{ $inpatient->room_name }} 
					<br>
					<small>{{ $inpatient->ward_name }}</small>
			</td>
			<td align='right'>
				@if ($admission->hasOpenConsultation($inpatient->patient_id, $inpatient->encounter_id)==0)
				<a class='btn btn-default btn-xs' href='{{ URL::to('consultations/create?encounter_id='. $inpatient->encounter_id) }}'>&nbsp;&nbsp;&nbsp;Start&nbsp;&nbsp;&nbsp;</a>
				@else
				<a class='btn btn-warning btn-xs' href='{{ URL::to('consultations/'. $admission->openConsultationId. '/edit') }}'>Resume</a>
				@endif
			</td>
	</tr>
@endforeach
</table>
@else
				<h4>
				<span class="label label-success">
				No case
				</span>
				</h4>
@endif
</div>
</div>
