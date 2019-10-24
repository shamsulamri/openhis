
@extends('layouts.app')

@section('content')
@if ($consultation && $view==false)
@include('consultations.panel')
@else
@include('patients.id_only')
@endif
<h1>
Medication Administration Records
</h1>
@if ($view)
<a href='/order_tasks/task/{{ $encounter->encounter_id }}/pharmacy' class='btn btn-primary'>Order Task</a>
<br>
@endif
<br>
<table class='table table-condensed'>
	<tbody>
@if (empty($drugs)) 
	<h3>No Record</h3>
@endif
@foreach ($drugs as $drug)
<?php
$frequency_count = count(explode(';',$drug->frequency_mar));
$servings = $order_helper->marServingCount($drug->order_id);
$cols = 7;
if (empty($encounter->discharge)) {
		$drug->order_quantity_supply = $order_helper->marUnitCount($drug->order_id);
}
?>
	<tr>
		<td width='30%'>
			@if ($drug->stop_id) <strike> @endif
			<h4>
			@if (!empty($encounter->discharge) && !$view) 
					<a href='/order_tasks/{{ $drug->order_id }}/edit?mar=true'>
					{{ $drug->product_name }}
					</a>
			@else
					{{ $drug->product_name }}
			@endif
			<small>
			<br>
			{{ $drug->product_code }}
			<br>
			{{ $order_helper->getPrescription($drug->order_id) }}
			<br>
			{{ DojoUtility::dateTimeReadFormat($drug->created_at) }}
			<br>
			{{ $drug->name }}
			</small>
			</h4>
			@if ($drug->stop_id) </strike> @endif

			Total Serving: {{ $servings }}<br>
			Total Unit: {{ $drug->order_quantity_supply }}
			<br>
			<br>
			@if (!$drug->stop_id && !$view)
			<a class='btn btn-warning btn-xs' href='{{ URL::to('/order_stop/create/'. $drug->order_id.'?drug=1') }}'>Stop</a>
			@endif

			@if ($drug->stop_id)
				Stop by {{ $drug->stop_by }}<br>on {{ DojoUtility::dateTimeReadFormat($drug->stop_date) }}
			@endif

		</td>
		<td>
		<!-- Adminstrations -->
			<table class='table table-bordered'>
			<!-- Date row -->
			<tr>
				<td width='100'>
				</td>
				@for ($i=0; $i<$cols; $i++)
<?php
	$date_value = DojoUtility::addDays(DojoUtility::dateReadFormat($start_date), $i);
	$date_label = DojoUtility::dateDayMonthFormat($date_value);	
	$time = 8;
?>
				<td width='10' align='center'>
					{{ $date_label }}
				</td>
				@endfor
			</tr>
			<!-- End -->
			@for ($f=0;$f<$frequency_count;$f++)
			<tr>
				<td width='10%' align='center'>
					{{ explode(";",$drug->frequency_mar)[$f] }}
				</td>
				<?php $time = $time+(24/$frequency_count); ?>
				@for ($i=0; $i<$cols; $i++)
				<?php
					$date_value = DojoUtility::addDays(DojoUtility::dateReadFormat($start_date), $i);
					$date_slot = $drug->order_id.'-'.$f.'-'.DojoUtility::dateYMDOnly($date_value);
					$date_ymd = DojoUtility::dateYMDOnly($date_value);
				?>
				<td width='120' align='center'>
			@if ($mars->contains('medication_slot',$date_slot)) 
				@if (empty($drug->stop_id))
						@if ($mars[$date_slot]->medication_fail)
								<span class='label label-danger'>
									Miss
								</span>
								<br>
						@endif
						@if (!$view)
						<a href='/medication_record/datetime/{{ $mars[$date_slot]->medication_id }}' data-toggle='tooltip' data-placement='top' title='Recorded by {{ $mars[$date_slot]->name }}'>
						@endif
				@endif

						{{ DojoUtility::timeReadFormat($mars[$date_slot]->medication_datetime) }}

				@if (empty($drug->stop_id) && !$view)
						</a>
				@endif

				<!-- Verification -->
				@if (!$verifications->contains('medication_slot',$date_slot) && empty($drug->stop_id) && !$view) 
						@if ($mars[$date_slot]->username != Auth::user()->username)
						<br>
						<br>
						<a href='/medication_record/verify/{{ $drug->order_id }}/{{ $f }}/{{ $date_ymd }}' class='btn btn-default btn-xs'>
							&nbsp;Witness&nbsp;
						</a>
						@endif
				@endif
				@if ($verifications->contains('medication_slot',$date_slot)) 
					<br><br>
					
					<span class='label label-success' data-toggle='tooltip' data-placement='top' title='Witnessed by {{ $verifications[$date_slot]->name }}'>Witnessed</span>
				@endif
			@else
					@if (DojoUtility::militaryFormat($date_value)==DojoUtility::militaryFormat($entry_start))
						@if (!$drug->stop_id && !$view)
							<a href='/medication_record/record/{{ $drug->order_id }}/{{ $f }}/{{ $date_ymd }}' class='btn btn-default btn-xs'>
								&nbsp;Record&nbsp;
							</a>
						@else
							-
						@endif
					@else 
						-
					@endif
			@endif
				</td>
				@endfor
			</tr>
			@endfor
			</table>
		<!-- End -->
		</td>
	</tr>
@endforeach
</tbody>
</table>
@endsection
