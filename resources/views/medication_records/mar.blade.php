
@extends('layouts.app')

@section('content')
@if ($consultation)
@include('consultations.panel')
@else
@include('patients.id_only')
@endif
<h1>
Medication Administration Records
</h1>
<br>
<table class='table table-condensed'>
	<tbody>
@if (empty($drugs)) 
	<h3>No Record</h3>
@endif
@foreach ($drugs as $drug)
<?php
$frequency_count = count(explode(';',$drug->frequency_mar));
?>
	<tr>
		<td width='30%'>
			@if ($drug->cancel_id) <strike> @endif
			<h4>

			{{ $drug->product_name }}
			<small>
			<br>
			{{ $order_helper->getPrescription($drug->order_id) }}
			<br>
			{{ DojoUtility::dateTimeReadFormat($drug->created_at) }}
			<br>
			{{ $drug->name }}
			</small>
			</h4>
			@if ($drug->cancel_id) </strike> @endif
			@if (!$drug->cancel_id)
			<a class='btn btn-warning btn-xs' href='{{ URL::to('/order_cancellations/create/'. $drug->order_id.'?drug=1') }}'>Cancel</a>
			@endif
		</td>
		<td>
		<!-- Adminstrations -->
			<table class='table table-bordered'>
			<!-- Date row -->
			<tr>
				<td width='100'>
				</td>
				@for ($i=0; $i<5; $i++)
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
				@for ($i=0; $i<5; $i++)
				<?php
					$date_value = DojoUtility::addDays(DojoUtility::dateReadFormat($start_date), $i);
					$date_slot = $drug->order_id.'-'.$f.'-'.DojoUtility::dateYMDOnly($date_value);
					$date_ymd = DojoUtility::dateYMDOnly($date_value);
				?>
				<td width='120' align='center'>
			@if ($mars->contains('medication_slot',$date_slot)) 
				@if (empty($drug->cancel_id))
				<a href='/medication_record/datetime/{{ $mars[$date_slot]->medication_id }}' data-toggle='tooltip' data-placement='top' title='Recorded by {{ $mars[$date_slot]->name }}'>
				@endif
						{{ DojoUtility::timeReadFormat($mars[$date_slot]->medication_datetime) }}
				@if (empty($drug->cancel_id))
				</a>
				@endif

				<!-- Verification -->
				@if (!$verifications->contains('medication_slot',$date_slot) && empty($drug->cancel_id)) 
						@if ($mars[$date_slot]->username != Auth::user()->username)
						<br>
						<br>
						<a href='/medication_record/verify/{{ $drug->order_id }}/{{ $f }}/{{ $date_ymd }}' class='btn btn-default btn-xs'>
							&nbsp;Verify&nbsp;
						</a>
						@endif
				@endif
				@if ($verifications->contains('medication_slot',$date_slot)) 
					<br><br>
					
					<span class='label label-success' data-toggle='tooltip' data-placement='top' title='Verified by {{ $verifications[$date_slot]->name }}'>Verified</span>
				@endif
			@else
					@if ($date_value==$entry_start)
						@if (!$drug->cancel_id)
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
