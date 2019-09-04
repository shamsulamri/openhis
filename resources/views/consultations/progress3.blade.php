
@extends('layouts.app')

@section('content')
<?php $encounter_id = 0; ?>
<style>
canvas {border:1px solid #e5e5e5}

table, th, td, tr {
		border: 1px solid #e5e5e5;	
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

{{ Form::select('encounter_list', $encounters, $target_encounter, ['id'=>'encounter_list','class'=>'form-control', 'onchange'=>'openEncounter()']) }}
<br>
@if (!empty($note->encounter->discharge))
<table width='100%'>
	<tr>
		<td bgcolor='#EFEFEF'>
		@if (!empty($note->encounter->discharge->discharge_diagnosis))
		<strong>Discharge diagnosis</strong><br>
		{{ $note->encounter->discharge->discharge_diagnosis }}
		<br>
		@endif
		@if (!empty($note->encounter->discharge->discharge_summary))
		<strong>Summary</strong><br>
		{{ $note->encounter->discharge->discharge_summary }}
		@endif
		</td>
	</tr>
</table>
<br>
@else
<br>
@endif

	<div class="row">
			<div class="col-xs-4">
{{ Form::select('consultation_list', $consultations,$target_consultation, ['id'=>'consultation_list','class'=>'form-control', 'size'=>'20', 'onchange'=>'openConsultation()']) }}
			</div>
			<div class="col-xs-8">
					<h4>
					Seen by {{ strtoupper($note->user->name) }}, 
					{{ DojoUtility::dateTimeReadFormat($encounterHelper->getConsultationDate($note->consultation_id)) }}
					</h4>
            		{{ Form::textarea('consultation_notes', $note?$note->consultation_notes:null, ['id'=>'consultation_notes', 'tabindex'=>1, 'class'=>'form-control','rows'=>'18']) }}
			</div>
	</div>


<script>

	function openConsultation() {
		target_consultation = document.getElementById('consultation_list').value;
		window.location.href = '/consultations/progress2/{{ $consultation->consultation_id}}/{{ $target_encounter }}/'+target_consultation;
	}

	function openEncounter() {
		target_encounter = document.getElementById('encounter_list').value;
		window.location.href = '/consultations/progress2/{{ $consultation->consultation_id}}/'+target_encounter;
	}
</script>
@endsection
