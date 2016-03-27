<div class='panel panel-default'>
<div class='panel-heading'>
<h4 class='panel-title'><strong>Inpatient</strong><h4>
</div>
<div class='panel-body'>
@if (count($inpatients)>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Date</th>
    <th>MRN</th>
    <th>Patient</th>
    <th>Bed</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($inpatients as $inpatient)
	<?php $status='' ?>
	@if ($admission->hasOpenConsultation($inpatient->patient_id)>0)
			<?php $status='warning' ?>
	@endif
	<tr class='{{ $status }}'>
			<td width='15%'>
					{{ date('d F, H:i', strtotime($inpatient->created_at)) }}
			</td>
			<td>
					{{ $inpatient->patient_mrn }}
			</td>
			<td>
					{{ $inpatient->patient_name }}
			</td>
			<td>
					{{ $inpatient->bed_name }} / {{ $inpatient->room_name }} / {{ $inpatient->ward_name }}
			</td>
			<td align='right'>
				@if ($admission->hasOpenConsultation($inpatient->patient_id)==0)
				<a class='btn btn-primary btn-xs' href='{{ URL::to('consultations/create?encounter_id='. $inpatient->encounter_id) }}'>Start Consultation</a>
				@else
				<a class='btn btn-warning btn-xs' href='{{ URL::to('consultations/'. $admission->openConsultationId. '/edit') }}'>Resume Consultation</a>
				@endif
			</td>
	</tr>
@endforeach
</tbody>
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
