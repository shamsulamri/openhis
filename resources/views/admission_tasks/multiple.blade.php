<table class="">
<?php 
$count=1; 
$checkFlag=False;
?>
@foreach ($multis as $multi)
<tr>
		<td width='30'>
		@if (!$multi->order_completed && !$checkFlag)
			{{ Form::checkbox("multi:".$multi->multiple_id, 1, $multi->order_completed) }}
			<?php $checkFlag=True; ?>
		@else
			{{ Form::hidden("multi:".$multi->multiple_id, 0) }}
		@endif
		</td>
		<td width='50'>
			{{ $count++ }} of {{ count($multis) }}
			
		</td>
		<td>
		@if (!$multi->order_completed)

		@else 
			Done on {{ DojoUtility::dateLongFormat($multi->updated_at) }} by {{ $multi->updatedBy->name }}
		@endif
		</td>
</tr>
	@if ($checkFlag) 
		@break
	@endif

@endforeach
</table>
