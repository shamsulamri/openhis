<h4>Inpatients</h4>
@if (count($inpatients)>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>MRN</th>
    <th>Patient</th>
    <th>Admission Date</th> 
    <th>Bed</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($inpatients as $inpatient)
	<?php $status='' ?>
	@if ($inpatient->hasOpenConsultation($inpatient->encounter->patient->patient_id)>0)
			<?php $status='warning' ?>
	@endif
	<tr class='{{ $status }}'>
			<td width='10%'>
					{{ $inpatient->encounter->patient->patient_mrn }}
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
<br>
