@extends('layouts.app')

@section('content')

<style>
audio {
	display:none;
}
</style>
@if (!$count>0) 
		@if ($orders->count()>$count)
				<audio controls autoplay>
						<source src="Positive.ogg" type="audio/mpeg">
						Your browser does not support the audio element.
				</audio> 
		@endif
@endif
<h1>
Report Queues
</h1>
<h3>{{ $location->location_name }}</h3>
<br>
<form action='/order_queues/report' method='post' class='form-horizontal'>
	<div class="row">
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'><div align='left'>Find</div></label>
						<div class='col-sm-9'>
							<input type='text' class='form-control' placeholder="Patient name or MRN" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'><div align='left'>Status</div></label>
						<div class='col-sm-9'>
								{{ Form::select('status_code', $status,$status_code, ['class'=>'form-control','maxlength'=>'10']) }}
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'><div align='left'>Encounter</div></label>
						<div class='col-sm-9'>
								{{ Form::select('encounter_code', $encounters,$encounter_code, ['class'=>'form-control','maxlength'=>'10']) }}
						</div>
					</div>
			</div>
	</div>
				<button class="btn btn-primary" type="submit" value="Submit">Search</button>
	<!--
	{{ Form::select('locations', $locations, $location, ['class'=>'form-control','maxlength'=>'10']) }}
	-->
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
	<input type='hidden' name="location_code" value="{{ $location_code }}">
</form>

<br>
@if ($orders->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th width='10'>Encounter</th>
    <th>Date</th>
    <th>EID</th>
    <th>Patient</th>
    <th>Product</th>
    <th>Ordered By</th>
    <th>Status</th>
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($orders as $order)
	<?php
		$order = $helper->getOrder($order->order_id);
	?>
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
					{{ DojoUtility::dateTimeReadFormat($order->consultation->created_at) }}
			</td>
			<td>
					{{ $order->encounter_id }}
			</td>
			<td>
					@if ($order->consultation->encounter->patient)
					{{ $order->consultation->encounter->patient->patient_name }}
					<br>
					<small>{{ $order->consultation->encounter->patient->patient_mrn }}</small>
					@endif
			</td>
			<td>
					{{ $order->product->product_name }}
			</td>
			<td>
					{{ $order->consultation->user->name }}

			</td>
			<td>
					{{ $order->order_report?'Completed':'-' }}

			</td>
			<td align='right'>
					<a href='{{ URL::to('order_tasks/'. $order->order_id) .'/edit?queue=report' }}' class='btn btn-primary'>
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
@if (isset($search) | isset($status_code)) 
	{{ $orders->appends(['search'=>$search, 'status_code'=>$status_code])->render() }}
	@else
	{{ $orders->render() }}
@endif
<br>
@if ($orders->total()>0)
	{{ $orders->total() }} records found.
@else
	No record found.
@endif

	
@endsection
