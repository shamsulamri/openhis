
@extends('layouts.app')

@section('content')
@include('patients.label')
<h2>
Progress Notes
</h2>
	<br>
	<div class="row">
			<div class="col-xs-6">
            	<a class="btn btn-default btn-lg" href="/consultations/{{ $consultation->consultation_id }}/edit" role="button">Back to Consultation</a>
			</div>
	</div>
@if (count($notes)>0)
<br>
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
							@foreach ($note->diagnoses as $diagnosis)
							<h4>
							<span class="label label-primary">
							{{ $diagnosis->diagnosis_clinical }}&nbsp;
							</span></h4> 
							@endforeach
					@endif
					@if (count($note->orders)>0)
							@foreach ($note->orders as $order)
							<span class="label label-success">
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

@endsection
