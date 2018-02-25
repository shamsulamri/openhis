@extends('layouts.app')

@section('content')

<style>
audio {
	display:none;
}
</style>
@if (!empty($count)) 
		@if ($order_queues->count()>$count)
				<audio controls autoplay>
						<source src="Positive.ogg" type="audio/mpeg">
						Your browser does not support the audio element.
				</audio> 
		@endif
@endif

@if ($is_discharge)
<h1>Future Orders</h1>
@else
<h1>Current Orders</h1>
@endif
<h3>{{ $location->location_name }}</h3>
<br>
<form action='/order_queue/search' method='post' class='form-horizontal'>
	<div class="row">
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'><div align='left'>Encounter</div></label>
						<div class='col-sm-9'>
								{{ Form::select('encounter_code', $encounters, $encounter_code, ['class'=>'form-control','maxlength'=>'10']) }}
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'>Status</label>
						<div class='col-sm-9'>
								{{ Form::select('status_code', $status,$status_code, ['class'=>'form-control','maxlength'=>'10']) }}
						</div>
					</div>
			</div>
	<div class="col-xs-4">
	<button class="btn btn-primary" type="submit" value="Submit">Search</button>
			</div>
	</div>
	<!--
	{{ Form::select('locations', $locations, $location, ['class'=>'form-control','maxlength'=>'10']) }}
	-->
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
	<input type='hidden' name="discharge" value="{{ $is_discharge }}">
</form>

<br>
@if ($order_queues->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th width='10'>Encounter</th>
    <th>Date</th>
    <th>Patient</th>
    <th>Orderer</th>
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($order_queues as $order)
	<tr>
			<td>
				<?php 
				$label = 'warning'; 
				switch ($order->consultation->encounter->encounter_code) {
						case "inpatient":
								$label = 'success';
								break;
						case "outpatient":
								$label = 'info';
								break;
						default:
								$label = 'default';
								break;
				}
				?>
				<span class='label label-{{ $label }}'>
				{{ $order->consultation->encounter->encounterType->encounter_name }}
				<span>
			</td>
			<td>
			@if ($is_discharge)
					{{ (DojoUtility::dateLongFormat($order->investigation_date)) }}
			@else
					{{ date('d F, H:i', strtotime($order->created_at)) }}
			@endif
			</td>
			<td>
					{{ $order->discharge_id }}
					{{ $order->consultation->encounter->patient->patient_name }}
					<br>
					<small>{{ $order->consultation->encounter->patient->patient_mrn }}</small>
			</td>
			<td>
					{{ $order->consultation->user->name }}<br>
					@if ($order->admission) 
					{{ $order->admission->bed->bed_name }}
						@if ($order->admission->bed->room)
								, {{ $order->admission->bed->room->room_name }}, 
						@endif
					{{ $order->admission->bed->ward->ward_name }}
					@else
					{{ $order->consultation->encounter->queue->location->location_name }}
					@endif						

			</td>
			<td align='right'>
					<a href='{{ URL::to('order_tasks/task/'. $order->encounter_id) .'/'. $order->location_code }}' class='btn btn-primary btn-xs'>
						Open	
					</a>
					@can('system-administrator')
					<a class='btn btn-danger btn-xs' href='{{ URL::to('order_queues/delete/'. $order->order_id) }}'>Delete</a>
					@endcan
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $order_queues->appends(['search'=>$search])->render() }}
	@else
	{{ $order_queues->render() }}
@endif
<br>
@if ($order_queues->total()>0)
	{{ $order_queues->total() }} records found.
@else
	No record found.
@endif

@if (!$is_discharge)
		@if (empty($encounter_code))
		<script>
		setTimeout(
				function() 
				{
						window.location.href = "/order_queues?count={{ $order_queues->count() }}";
				}
		,30000);
		</script>
		@endif
@endif
	
@endsection
