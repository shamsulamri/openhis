@extends('layouts.app')

@section('content')
<h1>Consultation Index</h1>
<br>
<form action='/consultation/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<br>
@if ($consultations->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Date</th>
    <th>Name</th> 
    <th>Room/Bed</th> 
	<th>Complain</th>
	</tr>
  </thead>
	<tbody>
@foreach ($consultations as $consultation)
	<tr height='80'>
			<td>
					{{ date('d F, H:i', strtotime($consultation->created_at)) }}
			</td>
			<td>
					{{$consultation->encounter->patient->patient_name}}<br>
					<small>{{$consultation->encounter->patient->patient_mrn}}</small>
			</td>
			<td>
					@if ($consultation->encounter->encounter_code=='inpatient')
						{{ $consultation->encounter->admission->bed->bed_name }} <br>
						<small>{{ $consultation->encounter->admission->bed->ward->ward_name }}</small> 
					@else
						{{ $consultation->encounter->queue->location->location_name }}
					@endif
			</td>
			<td>
					<a href='{{ URL::to('consultations/'. $consultation->consultation_id . '/edit') }}'>
						<small>
						{!! str_replace(chr(13), "<br>", $consultation->consultation_notes) !!}
						</small>
					</a>
			</td>
			<td align='right'>
					<a class='btn btn-default btn-xs' href='{{ URL::to('consultations/'. $consultation->consultation_id.'/edit') }}'>Edit Consultation</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $consultations->appends(['search'=>$search])->render() }}
	@else
	{{ $consultations->render() }}
@endif
<br>
@if ($consultations->total()>0)
	{{ $consultations->total() }} records found.
@else
	No record found.
@endif
@endsection