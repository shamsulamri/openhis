@extends('layouts.app')

@section('content')
<h1>Discharge List</h1>
<br>
<form action='/discharge/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<br>
<a href='/discharges/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($discharges->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>MRN</th>
    <th>Name</th> 
    <th>Type</th> 
    <th>Date</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($discharges as $discharge)
	<tr>
			<td width='10%'>
					<!--
					<a href='{{ URL::to('discharges/'. $discharge->discharge_id . '/edit') }}'>
					-->
						{{$discharge->patient_mrn}}
					<!--
					</a>
					-->
			</td>
			<td>
					{{$discharge->patient_name}}
			</td>
			<td>
					{{$discharge->type_name}}
			</td>
			<td>
					{{ date('d F Y, H:i', strtotime($discharge->created_at)) }}
			</td>
			<td align='right'>
					<?php
					$bill_label="Interim Bill";
					if ($discharge->discharge_id>0) {
						$bill_label = "Final Bill";
						if ($discharge->id>0) $bill_label="&nbsp;&nbsp; Paid &nbsp;&nbsp;";
					}
					?>
					<a class='btn btn-primary btn-xs' href='{{ URL::to('bill_items/'. $discharge->encounter_id) }}'>{{ $bill_label }}</a>
					@can('system-administrator')
					<a class='btn btn-danger btn-xs' href='{{ URL::to('discharges/delete/'. $discharge->discharge_id) }}'>Delete</a>
					@endcan
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
