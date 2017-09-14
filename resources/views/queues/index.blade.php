@extends('layouts.app')

@section('content')
<h1>Queue List</h1>
<form action='/queue/search' method='post' name='myform'>
	<!--
	<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<br>
	-->
	{{ Form::select('locations', $locations, $selectedLocation, ['class'=>'form-control','maxlength'=>'10','onchange'=>'reload()']) }}
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if ($queues->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Time</th> 
    <th>Patient</th>
    <th>Location</th>
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($queues as $queue)
	<tr>
			<td>
					{{ date('d F Y, H:i', strtotime($queue->created_at)) }}
					<br>
					<small>
					<?php $ago =$dojo->diffForHumans($queue->created_at); ?> 
					{{ $ago }}
					</small>	
			</td>
			<td>

							<a href='{{ URL::to('queues/'. $queue->queue_id . '/edit') }}'>
								{{strtoupper($queue->patient_name)}}
							</a>
					<br>
					<small>{{$queue->patient_mrn}}</small>
			</td>
			<td>
					{{$queue->location_name}}
			</td>
			<td align='right'>
					<a class='btn btn-default btn-lg' href='{{ URL::to('loans/request/'. $queue->patient_mrn.'?type=folder'.'&location_code='.$queue->location_code) }}'><span class='glyphicon glyphicon-folder-close' aria-hidden='true'></a>

					@can('module-consultation')
					<a class='btn btn-default btn-lg' title='Consultation' href='{{ URL::to('consultations/create?encounter_id='. $queue->encounter_id) }}'><span class='fa fa-stethoscope' aria-hidden='true'></span></a>
					@endcan
					@can('system-administrator')
					<a class='btn btn-danger btn-xs' href='{{ URL::to('queues/delete/'. $queue->queue_id) }}'>Delete</a>
					@endcan
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $queues->appends(['search'=>$search])->render() }}
	@else
	{{ $queues->render() }}
@endif
<br>
@if ($queues->total()>0)
	{{ $queues->total() }} records found.
@else
	No record found.
@endif
<script>
	function reload() {
			document.myform.submit();
	}
</script>
@endsection
