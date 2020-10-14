
@extends('layouts.app')

@section('content')
<?php $encounter_id = 0; ?>
<style>
canvas {border:1px solid #e5e5e5}
</style>
@if ($consultation)
@include('consultations.panel')
@else
@include('patients.id_only')
@endif
<h1>
Progress Notes
</h1>
<!--
<div class='pull-right'>
<input id="show_all" @if ($showAll=='true') checked="checked" @endif name="show_all" type="checkbox" value="1"> <label>Include empty notes</label>
</div>
-->
<input id="show_my_note" @if ($showNurse=='true') checked="checked" @endif name="show_my_note" type="checkbox" value="1"> <label>Show my notes only</label>
&nbsp;
<input id="show_physician" @if ($showPhysician=='true') checked="checked" @endif name="show_physician" type="checkbox" value="1"> <label>Show physician notes only</label>
<br>
{{ $notes->render() }}
@if (count($notes)>0)
<br>
<h3>
{{ $target_encounter->encounterType->encounter_name }} encounter on {{ DojoUtility::dateReadFormat($target_encounter->created_at) }}
<div class='pull-right'>
	{{ DojoUtility::diffForHumans($target_encounter->created_at) }}
</div>
</h3>
<table class="table">
	<tbody>
	@foreach ($notes as $nota)
<?php
		$note = $encounterHelper::getConsultation($nota->consultation_id);
?>
@if ($encounter_id != $note->encounter_id)
	@if (!empty($note->encounter->discharge))
	<tr>
			<td colspan=3 bgcolor='#EFEFEF'>
						@if (!empty($note->encounter->discharge->discharge_diagnosis))
						<strong>Discharge Diagnosis</strong><br>
						{{ $note->encounter->discharge->discharge_diagnosis }}
						@endif
						@if (!empty($note->encounter->discharge->discharge_summary))
								@if (!empty($note->encounter->discharge->discharge_diagnosis))
								<br>
								<br>
								@endif
								<strong>Summary</strong><br>
								{{ $note->encounter->discharge->discharge_summary }}
						@endif
			</td>
	</tr>
	@endif
@endif
	<tr>
			<td class='col-xs-2'>
			@if ($note->encounter->encounter_code == 'inpatient')
						<span class='badge badge-default'>
							Day {{ DojoUtility::diffInDaysBetweenDates($note->created_at, $note->encounter->created_at)+1 }}
						</span>
			@endif
					{{ DojoUtility::dateTimeReadFormat($encounterHelper->getConsultationDate($note->consultation_id)) }}
			</td>
			<td>
<a href="javascript:showHide({{ $nota->consultation_id }})">
					Seen by {{ strtoupper($note->user->name) }}
			<div class='pull-right'>
					@if (count($note->forms)>0)
						<span class='badge badge-default'>
						&nbsp;?&nbsp;
						</span>
					@endif
					@if ($nota->diagnoses>0)
						<span class='badge badge-success'>
						Dx
						</span>
					@endif
					@if ($nota->orders>0)
						<span class='badge badge-warning'>
						Ix
						</span>
					@endif
					@if ($nota->medications>0)
						<span class='badge badge-info'>
						Rx
						</span>
					@endif
					@if ($nota->annotations>0)
						<span class='badge badge-danger'>
						&nbsp;X&nbsp;
						</span>
					@endif
					@if (empty($note->encounter->discharge))
						<span class='badge badge-default'>
						{{ DojoUtility::diffForHumans($note->created_at) }}
						</span>
					@endif
			</div>
						
</a>
	<div id='consultation_{{ $nota->consultation_id }}'>	
					<br>
					@if ($note->consultation_notes)
            		{{ Form::textarea('consultation_notes', $note->consultation_notes, ['id'=>'consultation_notes', 'tabindex'=>1, 'class'=>'form-control','rows'=>'13']) }}
					<br>
					@else
						@if (count($note->annotations)==0)
						@endif
					@endif
					<!-- Annotations -->
					@if (count($note->annotations)>0)
							@foreach ($note->annotations as $annotation)
								@if ($note->created_at<$cutoff_date)
								<canvas tabindex=0 id="canvas_{{ $annotation->annotation_id }}" width="800" height="460"></canvas>
								@else
								<canvas tabindex=0 id="canvas_{{ $annotation->annotation_id }}" width="800" height="700"></canvas>
								@endif
							@endforeach
						
					<br>
					<br>
					@endif

					@if (count($note->forms)>0)
							<strong>Form</strong>
							@foreach ($note->forms as $form)
								<br>
								<a href='/graph/{{ $form->form_code }}/{{ $note->encounter_id }}'>
								<strong>{{ $form->form->form_name }}</strong>
								</a>
							@endforeach
							<br>
					@endif

					<!-- Diagnosis -->
					@if (count($note->diagnoses)>0)
					<strong>Diagnosis</strong>
							@foreach ($note->diagnoses as $diagnosis)
									<br>
									{{ $diagnosis->diagnosis_clinical }}
							@endforeach
					<br>
					<br>
					@endif
					
					<!-- Orders -->
					@if (count($note->orders)>0)
					<strong>Orders</strong>
					<br>
							@foreach ($note->orders as $order)
								@if ($order->orderCancel) <strike> @endif
								@if ($order->product)
								{{ $order->product->product_name }}
									@if ($order->product->category_code=='imaging' && $order->order_report)
									<a target="_blank" class='btn btn-success btn-xs' href="{{ Config::get('host.report_server')  }}/ReportServlet?report=order_report&id={{ $order->order_id }}">
										Report
									</a>
									,
									@endif
								@endif
								{{ $order_helper->getPrescription($order->order_id) }}
								<br>
								@if ($order->orderCancel) </strike> @endif
							@endforeach
					<br>
					@endif

					<!-- Medical Certificate -->
					@if ($note->medical_certificate)
					<br>
					<br>
					<strong>Medical Certificate</strong>
					<br>
						<?php $mc = $note->medical_certificate ?>

						@if ($mc)
								{{ DojoUtility::dateLongFormat($mc->mc_start) }}
								@if (!empty($mc->mc_end))
								- {{ DojoUtility::dateLongFormat($mc->mc_end) }}
								@endif
								@if (!empty($mc->mc_time_start))
								<br>
								{{ 'Time: '.$mc->mc_time_start }} - 
								{{ $mc->mc_time_end }}
								@endif
								<br>
								{{ 'Serial Number: '.$mc->mc_id }}
								@else
								<span class='label label-warning'>No medical certificate.</span>
						@endif
					@endif
					</div>
			</td>
	</tr>
	<?php $encounter_id = $note->encounter_id; ?>
@endforeach
</tbody>
</table>
@endif
@if (isset($showAll)) 
	{{ $notes->appends(['show_all'=>$showAll])->render() }}
	@else
	{{ $notes->render() }}
@endif
<br>
@if ($notes->total()>0)
	{{ $notes->total() }} records found.
@else
	No record found.
@endif

<script>
		function loadCanvas(id, dataURL) {
				var canvas = document.getElementById('canvas_'+id);
				var context = canvas.getContext('2d');
				var imageObj = new Image();
				imageObj.onload = function() {
						context.drawImage(
								this, 
								0,0,
								canvas.width, canvas.height);
				};

				imageObj.src = dataURL;

		}

	@foreach ($notes as $nota)
		<?php
				$note = $encounterHelper::getConsultation($nota->consultation_id);
		?>
		@foreach ($note->annotations as $annotation)
				loadCanvas({{ $annotation->annotation_id }}, '{{ $annotation->annotation_dataurl }}');
		@endforeach
	@endforeach
</script>
<script>
$(document).ready(function(){

			$('#show_all').click(function(){
					window.location.href = "?show_all="+this.checked+"&show_my_note="+$('#show_my_note').is(":checked");
			});

			$('#show_my_note').click(function(){
					window.location.href = "?show_all="+$('#show_all').is(":checked")+"&show_my_note="+this.checked;
			});

			$('#show_physician').click(function(){
					window.location.href = "?show_all="+$('#show_all').is(":checked")+"&show_physician="+this.checked;
			});

	@foreach ($notes as $nota)
		document.getElementById('consultation_{{ $nota->consultation_id }}').style.display = 'none';
	@endforeach
});

function showHide(id) {
  var x = document.getElementById("consultation_".concat(id));
  if (x.style.display === "none") {
    x.style.display = "block";
  } else {
    x.style.display = "none";
  }
}

</script>
@endsection
