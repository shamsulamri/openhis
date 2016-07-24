@extends('layouts.app')

@section('content')
<h1>Admission List</h1>
<h3>{{ $ward->ward_name }}</h3>
<!--
<br>
<form action='/admission/search' method='post'>
	{{ Form::select('ward', $wards, $ward, ['class'=>'form-control','maxlength'=>'10']) }}
	<br>
	<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<br>
	<button class="btn btn-primary" type="submit" value="Submit">Refresh</button>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
-->
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
@if ($admissions->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Admission Date</th>
    <th>Patient</th>
    <th>Bed</th>
    <th>Diet</th>
    <th>Consultant</th>
	@can('module-ward')
	@if ($setWard == $ward->ward_code)
	<th></th>
	@endif
	@endcan
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
					@if ($setWard == $ward->ward_code)
					<a href='{{ URL::to('admissions/'. $admission->admission_id . '/edit') }}'>
					@endif
					{{$admission->patient_name}}
					@if ($setWard == $ward->ward_code)
					</a>
					@endif	
					<br>
					<small>{{$admission->patient_mrn}}</small>
			</td>
			<td>
					{{$admission->bed_name}} / {{$admission->room_name}} 
					<br>
					{{$admission->ward_name}}
			</td>
			<td>
					{{$admission->diet_name}} / 
					{{$admission->class_name}}
			</td>
			<td>
					{{$admission->name}}
			</td>
			@can('module-ward')
			@if ($setWard == $ward->ward_code)
			<td align='right'>
					@if (is_null($admission->arrival_id)) 
							<a class='btn btn-warning btn-xs' href='{{ URL::to('ward_arrivals/create/'. $admission->encounter_id) }}'>Patient Arrive</a>
					@elseif (!is_null($admission->discharge_id))
							<a class='btn btn-success btn-xs' href='{{ URL::to('ward_discharges/create/'. $admission->admission_id) }}'>Discharge</a>
					@else
							<a class='btn btn-default btn-xs' href='{{ URL::to('loans/request/'. $admission->patient_mrn.'?type=folder') }}'>Folder Request</a>
							<br>
							<a class='btn btn-default btn-xs' href='{{ URL::to('admission_beds?flag=1&admission_id='. $admission->admission_id) }}'>Bed Movement</a>
							<br>
							<a class='btn btn-default btn-xs' href='{{ URL::to('bed_bookings/create/'. $admission->patient_id.'/'.$admission->admission_id) }}'>&nbsp; Bed Booking &nbsp; </a>
					@endif

					@can('system-administrator')
							<a class='btn btn-danger btn-xs' href='{{ URL::to('admissions/delete/'. $admission->admission_id) }}'>Delete</a>
					@endcan
			</td>
			@endcan
			@endif
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
