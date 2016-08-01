@extends('layouts.app')

@section('content')
<h1>Admission List</h1>
@can('module-ward')
<h3>{{ $ward->ward_name }}</h3>
@endcan
@can('module-patient')
<br>
<form action='/admission/search' method='post'>
	<!--
	{{ Form::select('ward', $wards, $ward, ['class'=>'form-control','maxlength'=>'10']) }}
	<br>
	-->
	<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<br>
	<button class="btn btn-primary" type="submit" value="Submit">Refresh</button>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
@endcan
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
@if ($admissions->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Date</th>
	@can('module-ward')
    <th></th>
	@endcan
    <th>Patient</th>
    <th>Bed</th>
	@can('module-ward')
    <th>Diet</th>
	@endcan
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
	@can('module-ward')
	@if (is_null($admission->arrival_id)) 
			<?php $status='warning' ?>
	@endif
	@if (!is_null($admission->discharge_id)) 
			<?php $status='success' ?>
	@endif
	@endcan
	<tr class='{{ $status }}'>
			<td>
					{{ date('d F, H:i', strtotime($admission->created_at)) }}
					<br>
					<small>
					<?php $ago =$dojo->diffForHumans($admission->created_at); ?> 
					{{ $ago }}
					</small>	
			</td>
			@can('module-ward')
			@if ($setWard == $ward->ward_code)
			<td align='right' width='5'>
				<div class="btn-group">
				  <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<span class="glyphicon glyphicon-menu-hamburger"></span>
				  </button>
				  <ul class="dropdown-menu">
					@if (is_null($admission->arrival_id)) 
							<li><a href='{{ URL::to('ward_arrivals/create/'. $admission->encounter_id) }}'>Log Arrival</a></li>
					@elseif (!is_null($admission->discharge_id))
							<li><a href='{{ URL::to('ward_discharges/create/'. $admission->admission_id) }}'>Discharge</a></li>
					@else
					<li><a href='{{ URL::to('loans/request/'. $admission->patient_mrn.'?type=folder') }}'>Folder Request</a></li>
					<li><a href='{{ URL::to('admission_beds?flag=1&admission_id='. $admission->admission_id) }}'>Bed Movement</a></li>
					<li><a href='{{ URL::to('bed_bookings/create/'. $admission->patient_id.'/'.$admission->admission_id) }}'>Bed Booking</a></li>
					@endif
					<li role='separator' class='divider'></li>
					<li><a href='{{ URL::to('admissions/'. $admission->admission_id . '/edit') }}'>Edit Admission</a></li>
				  </ul>
				</div>
			</td>
			@endif
			@endcan
			<td>
					{{ strtoupper($admission->patient_name) }}
					<br>
					<small>{{$admission->patient_mrn}}
					<br>
					{{$admission->name}}</small>
			</td>
			<td>
					{{$admission->bed_name}} / {{$admission->room_name}} 
					<br>
					<small>{{$admission->ward_name}}<small>	
			</td>
			@can('module-ward')
			<td>
					{{$admission->diet_name}}
			</td>
			@endcan
			@can('module-ward')
			@if ($setWard == $ward->ward_code)
			<td>
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
