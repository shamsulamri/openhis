
@extends('layouts.app')

@section('content')
@include('consultations.panel')
<h1>
Progress Notes
</h1>
@if (count($notes)>0)
<br>
<table class="table table-hover">
	<tbody>
	@foreach ($notes as $note)
	<tr>
			<td class='col-xs-2'>
					{{ date('d F Y, H:i', strtotime($note->created_at)) }} 
			</td>
			<td>
					<strong>Seen by {{ $note->user->name }}</strong>
					<br>
					{!! str_replace(chr(13), "<br>", $note->consultation_notes) !!}
					<br>
					@if (count($note->diagnoses)>0)
							<h4>
							@foreach ($note->diagnoses as $diagnosis)
							<span class="label label-primary">
							{{ $diagnosis->diagnosis_clinical }}
							</span> &nbsp;
							@endforeach
							</h4>
					@endif
					@if (count($note->orders)>0)
							@foreach ($note->orders as $order)
								@if (!empty($order->orderCancel->cancel_id))
									<span class="label label-danger">
								@else
									<span class="label label-success">
								@endif
								{{ $order->product->product_name }}
								</span>&nbsp; 
							@endforeach
					@endif
			</td>
	</tr>
@endforeach
</tbody>
</table>
@endif
<br>
{{ $notes->render() }}
@endsection
