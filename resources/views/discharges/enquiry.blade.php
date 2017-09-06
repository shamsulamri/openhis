@extends('layouts.app')

@section('content')
<h1>Discharge Enquiry</h1>
<form action='/discharge/enquiry' method='post' class='form-inline'>
	<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<label>Encounter</label>
	{{ Form::select('encounter_code', $encounter_types, $encounter_code, ['class'=>'form-control','maxlength'=>'10']) }}
	<label>Discharge</label>
	{{ Form::select('type_code', $discharge_types, $type_code, ['class'=>'form-control','maxlength'=>'10']) }}
	<button class="btn btn-primary" type="submit" value="Submit">Search</button>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@can('system-administrator')
<a href='/discharges/create' class='btn btn-primary'>Create</a>
<br>
@endcan
<br>
@if ($discharges->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Encounter Date</th>
    <th>Discharge Date</th>
    <th>Name</th> 
    <th>Encounter</th>
    <th>Ward</th>
    <th>LOS</th>
    <th>Outcome</th> 
    <th>Consultant</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($discharges as $discharge)
	<tr>
			<td>
					{{ DojoUtility::dateTimeReadFormat($discharge->encounter->created_at) }}
			</td>
			<td>
					{{ (DojoUtility::dateTimeReadFormat($discharge->created_at)) }}
					<br>
					<small>
					<?php $ago =$dojo->diffForHumans($discharge->created_at); ?> 
					{{ $ago }}
					</small>	
			</td>
			<td>
					{{ strtoupper($discharge->patient_name) }}
					<br>
					<small>{{$discharge->patient_mrn}}</small>
			</td>
			<td>
					{{ $discharge->encounter->encounterType->encounter_name }}
			</td>
			<td>
					@if ($discharge->encounter->admission)
					{{ $discharge->encounter->admission->bed->ward->ward_name }}
					@else
					-
					@endif
			</td>
			<td>
					@if ($discharge->encounter->admission)
					{{ DojoUtility::dateDiff($discharge->encounter->created_at, $discharge->created_at) }} day
					@else
					-
					@endif
			
			</td>
			<td>
					{{$discharge->type_name}}

			</td>
			<td>
					{{$discharge->name}}
					<br>
					<small>{{$discharge->ward_name}}</small>
			</td>
			<td align='right'>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $discharges->appends(['search'=>$search])->render() }}
	@else
	{{ $discharges->render() }}
@endif
<br>
@if ($discharges->total()>0)
	{{ $discharges->total() }} records found.
@else
	No record found.
@endif
@endsection
