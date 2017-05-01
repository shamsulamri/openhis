<table class="">
<?php $count=1; ?>
@foreach ($multis as $multi)
<tr>
		<td width='30'>
		@if (!$multi->order_completed)
			{{ Form::checkbox("multi:".$multi->multiple_id, 1, $multi->order_completed) }}
		@endif
		</td>
		<td width='20'>
			{{ $count++ }}.
			
		</td>
		<td>
		@if (!$multi->order_completed)
			-			
		@else 
			Done on {{ DojoUtility::dateLongFormat($multi->updated_at) }}
		@endif
		</td>
</tr>
@endforeach
</table>
