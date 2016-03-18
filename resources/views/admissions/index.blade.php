@extends('layouts.app')

@section('content')
<h1>Admission Index</h1>
<br>
<form action='/admission/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<br>
@if ($admissions->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>MRN</th>
    <th>Patient</th>
    <th>Bed</th>
    <th>Room</th> 
    <th>Ward</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($admissions as $admission)
	<?php $status='' ?>
	@if (!is_null($admission->discharge_id)) 
			<?php $status='success' ?>
	@endif
	@if (is_null($admission->arrival_id)) 
			<?php $status='warning' ?>
	@endif
	<tr class='{{ $status }}'>
			<td>
					{{$admission->patient_mrn}}
			</td>
			<td>
{{ $admission->ward_discharge }}
					{{$admission->patient_name}}
			</td>
			<td>
					<a href='{{ URL::to('admissions/'. $admission->admission_id . '/edit') }}'>
						{{$admission->bed_name}}
					</a>
			</td>
			<td>
					{{$admission->room_name}}
			</td>
			<td>
					{{$admission->ward_name}}
			</td>
			<td align='right'>
			@if (is_null($admission->arrival_id)) 
					<a class='btn btn-warning btn-xs' href='{{ URL::to('ward_arrivals/create/'. $admission->encounter_id) }}'>Arrive</a>
			@elseif (!is_null($admission->arrival_id))
					<a class='btn btn-primary btn-xs' href='{{ URL::to('consultations/create?encounter_id='. $admission->encounter_id) }}'>Consultation</a>
			@elseif (!is_null($admission->arriaval_id))
					<a class='btn btn-warning btn-xs' href='{{ URL::to('ward_discharges/create/'. $admission->encounter_id) }}'>Discharge</a>
			@endif
					<a class='btn btn-danger btn-xs' href='{{ URL::to('admissions/delete/'. $admission->admission_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $admissions->appends(['search'=>$search])->render() }}
	@else
	{{ $admissions->render() }}
@endif
<br>
@if ($admissions->total()>0)
	{{ $admissions->total() }} records found.
@else
	No record found.
@endif
@endsection
