@extends('layouts.app')

@section('content')
<h1>Admission Index</h1>
<br>
<form action='/admission/search' method='post'>
	{{ Form::select('ward', $wards, $ward, ['class'=>'form-control','maxlength'=>'10']) }}
	<br>
	<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<br>
	<button class="btn btn-primary" type="submit" value="Submit">Search</button>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
	<br>
@endif
@if ($admissions->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Date</th>
    <th>Patient</th>
    <th>Consultant</th>
    <th>Bed</th>
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
					{{ date('d F, H:i', strtotime($admission->created_at)) }}
			</td>
			<td>
					<a href='{{ URL::to('admissions/'. $admission->admission_id . '/edit') }}'>
					{{$admission->patient_name}}
					</a>
					<br>
					<small>{{$admission->patient_mrn}}</small>
			</td>
			<td>
					{{$admission->name}}
			</td>
			<td>
					{{$admission->bed_name}} / {{$admission->room_name}} 
					<br>
					{{$admission->ward_name}}
			</td>
			<td align='right'>
					<a class='btn btn-default btn-xs' href='{{ URL::to('admission_beds?admission_id='. $admission->admission_id) }}'>Bed Movement</a>
			@if (is_null($admission->arrival_id)) 
					<a class='btn btn-warning btn-xs' href='{{ URL::to('ward_arrivals/create/'. $admission->encounter_id) }}'>Arrive</a>
			@elseif (!is_null($admission->discharge_id))
					<a class='btn btn-success btn-xs' href='{{ URL::to('ward_discharges/create/'. $admission->encounter_id) }}'>Discharge</a>
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
