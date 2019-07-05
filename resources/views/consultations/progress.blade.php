
@extends('layouts.app')

@section('content')
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
<div class='pull-right'>
<input id="show_all" @if ($showAll=='true') checked="checked" @endif name="show_all" type="checkbox" value="1"> <label>Include empty notes</label>
</div>
<input id="show_my_note" @if ($showNurse=='true') checked="checked" @endif name="show_my_note" type="checkbox" value="1"> <label>Show my notes only</label>
<br>
{{ $notes->render() }}
@if (count($notes)>0)
<br>
<table class="table">
	<tbody>
	@foreach ($notes as $nota)
<?php
		$note = $encounterHelper::getConsultation($nota->consultation_id);
?>
	<tr>
			<td class='col-xs-2'>
					<h3>
					{{ DojoUtility::dateTimeReadFormat($encounterHelper->getConsultationDate($note->consultation_id)) }}
					</h3>
			</td>
			<td>
					<h3>Seen by {{ $note->user->name }}</h3>
					@if ($note->consultation_notes)
            		{{ Form::textarea('consultation_notes', $note->consultation_notes, ['id'=>'consultation_notes', 'tabindex'=>1, 'class'=>'form-control','rows'=>'13', 'style'=>'font-size: 1.2em']) }}
					<br>
					@else
						@if (count($note->annotations)==0)
							-
							<br>
							<br>
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
							<br>
					@endif

					<!-- Diagnosis -->
					@if (count($note->diagnoses)>0)
					<strong>Diagnosis</strong>
					<br>
							@foreach ($note->diagnoses as $diagnosis)
									{{ $diagnosis->diagnosis_clinical }}
									<br>
							@endforeach
					<br>
					@endif
					
					<!-- Orders -->
					@if (count($note->orders)>0)
					<strong>Orders</strong>
					<br>
							@foreach ($note->orders as $order)
								@if ($order->orderCancel) <strike> @endif
								@if ($order->product)
								{{ $order->product->product_name }},
								@endif
								{{ $order_helper->getPrescription($order->order_id) }}
								<br>
								@if ($order->orderCancel) </strike> @endif
							@endforeach
					<br>
					<br>
					@else
							-
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
			</td>
	</tr>
@endforeach
</tbody>
</table>
@endif
<br>
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
});
</script>
@endsection
