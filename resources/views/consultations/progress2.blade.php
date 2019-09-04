
@extends('layouts.app')

@section('content')
<?php $encounter_id = 0; ?>
<style>
canvas {border:1px solid #e5e5e5}

table, th, td, tr {
		border: 0px solid #e5e5e5;	
		padding: 10px;
}
tr.border_bottom td {
  border-bottom:1pt solid #EFEFEF;
}
</style>
@if ($consultation)
@include('consultations.panel')
@else
@include('patients.id_only')
@endif
<h1>
Progress Notes 
</h1>

<br>
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Date</th>
    <th>Encounter</th>
    <th>Discharged By<div class='pull-right'>Duration</div></th> 
	</tr>
  </thead>
	<tbody>
@foreach($encounters as $encounter) 
	<tr>
		<td width='130'>
				{{ DojoUtility::dateTimeReadFormat($encounter->created_at) }} 
		</td>
		<td width='100'>
				{{ $encounter->encounterType->encounter_name }} 
		</td>
		<td>
				@if ($encounter->encounter_id == $consultation->encounter_id)
					<a href='/consultations/progress/{{ $consultation->consultation_id }}/{{ $encounter->encounter_id }}?show_all=false&show_physician=true'>
						Current encounter
					</a>
				@else
						<a href="javascript:showHide({{ $encounter->encounter_id }})">
								@if ($encounter->discharge)
								{{ $encounter->discharge->consultation->user->name }}
								@endif
						</a>
				@endif
				<div class='pull-right'>
					{{ DojoUtility::diffForHumans($encounter->created_at) }}
				</div>
	@if (!empty($encounter->discharge))
		<div id='encounter_{{ $encounter->encounter_id }}'>	
		@if (!empty($encounter->discharge->discharge_diagnosis))
		<strong>Discharge Diagnosis</strong><br>
		{{ $encounter->discharge->discharge_diagnosis }}
		@endif
		@if (!empty($encounter->discharge->discharge_summary))
			@if (!empty($encounter->discharge->discharge_diagnosis))
				<br>
				<br>
			@endif
				<strong>Discharge Summary</strong><br>
				{{ $encounter->discharge->discharge_summary }}
		@endif
		@if (empty($encounter->discharge->discharge_summary) && empty($encounter->discharge->discharge_diagnosis))
		<br>
		@endif
		@if (!empty($encounter->discharge->discharge_summary))
		<br>
		@endif
		<a href='/consultations/progress/{{ $consultation->consultation_id }}/{{ $encounter->encounter_id }}?show_all=false&show_physician=true'>See notes</a>
		</div>
	@endif

	</tr>
@endforeach
	</tbody>
</table>

{{ $encounters->render() }}
<br>
@if ($encounters->total()>0)
	{{ $encounters->total() }} records found.
@else
	No record found.
@endif
<script>
$(document).ready(function(){
		@foreach ($encounters as $encounter)
				@if (!empty($encounter->discharge))
				document.getElementById('encounter_{{ $encounter->encounter_id }}').style.display = 'none';
				@endif
		@endforeach

});

function showHide(id) {
		var x = document.getElementById("encounter_".concat(id));
		if (x.style.display === "none") {
				x.style.display = "block";
		} else {
				x.style.display = "none";
		}
}
</script>
@endsection
