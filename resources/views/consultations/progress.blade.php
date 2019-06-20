
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
{{ $notes->render() }}
@if (count($notes)>0)
<br>
<table class="table">
	<tbody>
	@foreach ($notes as $note)
	<tr>
			<td class='col-xs-2'>
					{{ date('d F Y, H:i', strtotime($note->created_at)) }} 
			</td>
			<td>
					<strong>Seen by {{ $note->user->name }}</strong>
					<br>
					@if ($note->consultation_notes)
					{!! str_replace(chr(13), "<br>", $note->consultation_notes) !!}
					<br>
					@else
						@if (count($note->annotations)==0)
							-
						@endif
					@endif
					@if (count($note->annotations)>0)
					<br>
							@foreach ($note->annotations as $annotation)
								<canvas tabindex=0 id="canvas_{{ $annotation->annotation_id }}" width="800" height="350"></canvas>
							@endforeach
					@endif

					@if (count($note->forms)>0)
							<br>
							<br>
							<strong>Form</strong>
							@foreach ($note->forms as $form)
								<br>
								<a href='/graph/{{ $form->form_code }}/{{ $note->encounter_id }}'>
								<strong>{{ $form->form->form_name }}</strong>
								</a>
							@endforeach
					@endif

					<!-- Diagnosis -->
					<br>
					<br>
					<strong>Diagnosis</strong>
					<br>
					@if (count($note->diagnoses)>0)
							@foreach ($note->diagnoses as $diagnosis)
									{{ $diagnosis->diagnosis_clinical }}
									<br>
							@endforeach
					@else
							-
							<br>
					@endif
					
					<!-- Orders -->
					<br>
					<strong>Orders</strong>
					<br>
					@if (count($note->orders)>0)
							@foreach ($note->orders as $order)
								@if ($order->orderCancel) <strike> @endif
								@if ($order->product)
								{{ $order->product->product_name }},
								@endif
								{{ $order_helper->getPrescription($order->order_id) }}
								<br>
								@if ($order->orderCancel) </strike> @endif
							@endforeach
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
<!--
<br>
{{ $notes->render() }}
-->

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

	@foreach ($notes as $note)
		@foreach ($note->annotations as $annotation)
				loadCanvas({{ $annotation->annotation_id }}, '{{ $annotation->annotation_dataurl }}');
		@endforeach
	@endforeach
</script>
@endsection
