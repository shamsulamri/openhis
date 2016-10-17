@extends('layouts.app')

@section('content')
<h1>Discharge List</h1>
<br>
<form action='/discharge/search' method='post' class='form-inline'>
	<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<label>Discharge Type</label>
	{{ Form::select('type_code', $discharge_types, $type_code, ['class'=>'form-control','maxlength'=>'10']) }}
	<button class="btn btn-default" type="submit" value="Submit">Search</button>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/discharges/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($discharges->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Date</th>
    <th>Name</th> 
    <th>Discharge</th> 
    <th>Physician</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($discharges as $discharge)
	<tr>
			<td>
					{{ date('d F Y', strtotime($discharge->created_at)) }}
					<br>
					<small>
					<?php $ago =$dojo->diffForHumans($discharge->created_at); ?> 
					{{ $ago }}
					</small>	
					<!--
					<a href='{{ URL::to('discharges/'. $discharge->discharge_id . '/edit') }}'>
					-->
					<!--
					</a>
					-->
			</td>
			<td>
					{{ strtoupper($discharge->patient_name) }}
					<br>
					<small>{{$discharge->patient_mrn}}</small>
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
			@if ($dischargeHelper->drugCompleted($discharge->encounter_id))

					<?php
					$bill_label="Interim Bill";
					$button_type="primary";
					if ($discharge->discharge_id>0) {
						$bill_label = "Final Bill";
						if ($discharge->id>0) {
								$bill_label="&nbsp;&nbsp; Paid &nbsp;&nbsp;";
								$button_type="default";
						}
					}
					?>
				
					@can('module-discharge')
					<a class='btn btn-{{ $button_type }} btn-xs' href='{{ URL::to('bill_items/'. $discharge->encounter_id) }}'>{{ $bill_label }}</a>
					@endcan
					@can('system-administrator')
					<a class='btn btn-danger btn-xs' href='{{ URL::to('discharges/delete/'. $discharge->discharge_id) }}'>Delete</a>
					@endcan
			@else
					<span class="label label-warning">
					Preparing drug...
					</span>
			@endif
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
