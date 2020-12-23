@extends('layouts.app')

@section('content')
@include('consultations.panel')
<h1>Patient Histories</h1>
<br>
<?php $active = ''; ?>
<div class="tabs-container">
		<ul class="nav nav-tabs">
				<li class="active"><a data-toggle="tab" href="#tab_cp">Pathway</a></li>
				@foreach ($histories as $history)
				<li class="{{ $active }}"><a data-toggle="tab" href="#tab_{{ $history->history_code }}">{{ str_replace("History", "", $history->history_name) }}</a></li>
<?php $active = ""; ?>
				@endforeach
		</ul>
		<div class="tab-content">
<?php $active = ''; ?>
			<div id="tab_cp" class="tab-pane active">
<?php $active = ""; ?>
				<div class="panel-body">
<iframe 
		style="border:0px #d8d8d8 solid"
		src="http://localhost:8084/editor/{{ $consultation->patient_id }}/{{ $consultation->consultation_id }}?soap=pmh&view=true"
		width='100%'
		height="326">
</iframe>
				</div>
			</div>
			@foreach ($histories as $history)
<?php
	$note = "";
	if (!empty($consultation_histories[$history->history_code])) {
			$note = $consultation_histories[$history->history_code];
	}
?>
			<div id="tab_{{ $history->history_code }}" class="tab-pane {{ $active }}">
<?php $active = ""; ?>
				<div class="panel-body">
	{{ Form::textarea($history->history_code, 
				$note,
				['id'=>$history->history_code, 'name'=>$history->history_code, 'class'=>'form-control','rows'=>'15', 'style'=>'border:none;color:#000000;font-size: 1.1em']) }}
				</div>
			</div>
			@endforeach
		</div>
</div>

<br>
<button class='btn btn-primary'>Save</button>
<div id='saveHTML' class='text text-success pull-right'></div>

<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
		keypressCount = 0;
$(document).ready(function(){

			$(document).on('focusout', 'textarea', function(e) {
					var id = e.currentTarget.name;
					updateHistory(id);
			});

			$(document).on('keypress', 'textarea', function(e) {
					$('#saveHTML').html("");
					historyCode = e.currentTarget.name;
					keypressCount += 1;
					console.log(keypressCount);
					if (keypressCount>50) {
							updateHistory(historyCode);
							keypressCount=0;
					}


					if (e.key=='.') {
							updateHistory(historyCode);
							keypressCount=0;
					}

					if (e.charCode==13) {
							updateHistory(historyCode);
							keypressCount=0;
					}
			});

			function updateHistory(historyCode) {
					var historyNote = $('#'.concat(historyCode)).val();
					historyNote = encodeURIComponent(historyNote);

					var dataString = "history_code="+historyCode+"&history_note="+historyNote+"&patient_id={{ $consultation->patient_id }}";

					$.ajax({
						type: "POST",
						headers: {'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')},
						url: "{{ route('history.post') }}",
						data: dataString,
						success: function(data){
							$('#saveHTML').html("Saved...");
							console.log("History saved....");
						}
					});

			}

});
</script>
@endsection
