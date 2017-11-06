
@extends('layouts.app')

@section('content')
@if ($consultation)
@include('consultations.panel')
@else
@include('patients.id_only')
@endif
<h1>
Medication Administration Record
</h1>
<table class='table table-condensed'>
	<tbody>
@foreach ($drugs as $drug)
<br>
	<tr>
		<td width='30%'>
			{{ $drug->product_name }}
			<br>
			{{ $order_helper->getPrescription($drug->order_id) }}
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
			@for ($f=0;$f<$drug->frequency_value;$f++)
			<tr>
				<td width='10%'>
					{{ explode(";",$drug->frequency_mar)[$f] }}
				</td>
				<?php $time = $time+(24/$drug->frequency_value); ?>
				@for ($i=0; $i<5; $i++)
				<?php
					$date_value = DojoUtility::addDays(DojoUtility::dateReadFormat($start_date), $i);
					$date_slot = $drug->order_id.'-'.$f.'-'.DojoUtility::dateYMDOnly($date_value);
					$date_ymd = DojoUtility::dateYMDOnly($date_value);
				?>
				<td width='10' align='center'>
			@if ($mars->contains('medication_slot',$date_slot)) 
				{{ DojoUtility::timeReadFormat($mars[$date_slot]->medication_datetime) }}
				<br>by 
				{{ $mars[$date_slot]->username }}
			@else
					@if ($date_value>=$now)
					<a href='/medication_record/record/{{ $drug->order_id }}/{{ $f }}/{{ $date_ymd }}' class='btn btn-default btn-xs'>
						&nbsp;?&nbsp;
					</a>
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
