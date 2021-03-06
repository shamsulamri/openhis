@extends('layouts.app')

@section('content')

<h1>Queue List</h1>
<form action='/queue/search' method='post' name='myform' class='form-horizontal'>
	<div class="row">
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-4 control-label'><div align='left'>Encounter&nbsp;</div></label>
						<div class='col-sm-8'>
								{{ Form::select('encounter_code', $encounters, $encounter_code, ['class'=>'form-control','maxlength'=>'10']) }}
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-4 control-label'>Location</label>
						<div class='col-sm-8'>
								{{ Form::select('locations', $locations, $selectedLocation, ['class'=>'form-control','maxlength'=>'10']) }}
						</div>
					</div>
			</div>
			<div class="col-xs-4">
	<button class="btn btn-primary" type="submit" value="Submit">Search</button>
			</div>
	</div>
	<!--
	<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<br>
	-->
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
@if ($queues->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Time</th> 
    <th>Triage</th> 
    <th>Patient</th>
    <th>Panel</th>
    <th>Location</th>
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($queues as $queue)
	@if ($queue->encounter)
	<tr>
			<td width='15%'>
					{{ date('d F Y, H:i', strtotime($queue->created_at)) }}
					<br>
					<small>
					<?php $ago =$dojo->diffForHumans($queue->created_at); ?> 
					{{ $ago }}
					</small>	
			</td>
			<td width='35'>
					@if ($queue->encounter->triage)
						<table>
							<tr>
								<td bgcolor='{{ $queue->encounter->triage->triage_color }}' width='35' height='35' align='center'>
								</td>
							</tr>
						</table>
					@endif
			</td>
			<td>
					<a href='{{ URL::to('queues/'. $queue->queue_id) }}'>
						{{strtoupper($queue->patient_name)}}
					</a>
					<br>
					<small>{{$queue->patient_mrn}}</small>
			</td>
			<td>
					@if ($queue->encounter->sponsor)
						{{ $queue->encounter->sponsor->sponsor_name }}
					@endif
			</td>
			<td>
					{{$queue->location_name}}
			</td>
			<td align='right'>
					<!--
					<a class='btn btn-default btn-lg' href='{{ URL::to('loans/request/'. $queue->patient_mrn.'?type=folder'.'&location_code='.$queue->location_code) }}'><span class='glyphicon glyphicon-folder-close' aria-hidden='true'></a>
					-->

					@can('system-administrator')
							@if ($queue->consultations()==0)
							<a class='btn btn-danger' href='{{ URL::to('queues/delete/'. $queue->queue_id) }}'>Delete</a>
							@endif
					@endcan
					@can('module-consultation')
					<a class='btn btn-primary' title='Start consultation' href='{{ URL::to('consultations/confirm/'. $queue->encounter_id) }}'>
						<i class="fa fa-stethoscope"></i>
					</a>
					@endcan
			</td>
	</tr>
	@endif
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
