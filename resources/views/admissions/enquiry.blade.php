@extends('layouts.app')

@section('content')
<h1>Admission Enquiry</h1>

<form action='/admission/enquiry' method='post' class='form-inline'>
	<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<label>Ward</label>
	{{ Form::select('ward', $wards, $ward, ['class'=>'form-control','maxlength'=>'10']) }}
	<label>Type</label>
	{{ Form::select('admission_code', $admission_type, $admission_code, ['class'=>'form-control','maxlength'=>'10']) }}
	<button class="btn btn-primary" type="submit" value="Submit">Search</button>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($admissions->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Date</th>
    <th>Patient</th>
    <th>Gender</th>
    <th>Bed</th>
    <th>Consultant</th>
	</tr>
  </thead>
	<tbody>
@foreach ($admissions as $admission)
	<?php 
	$status='';
	$patient = $admission->encounter->patient;
	?>
	<tr class='{{ $status }}'>
			<td>
					{{ DojoUtility::dateTimeReadFormat($admission->admission_date) }}
					<br>
					<small>
					<?php $ago =DojoUtility::diffForHumans($admission->admission_date); ?> 
					{{ $ago }}
					</small>	
			</td>
			<td>
					{{ strtoupper($patient->patient_name) }}
					<br>
					<small>{{$patient->patient_mrn}}</small>
			</td>
			<td>
					{{ $patient->gender->gender_name }}
			</td>
			<td>
					{{ $admission->bed->bed_name }} 
					@if ($admission->bed->room) 
						/ {{$admission->bed->room->room_name}} 
					@endif
					<br>
					<small>{{$admission->bed->ward->ward_name}}</small>	
			</td>
			<td>
					{{$admission->consultant->name }}
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
