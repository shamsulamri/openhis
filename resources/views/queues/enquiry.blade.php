@extends('layouts.app')

@section('content')
<h1>Queue Enquiry</h1>
<form id='form' action='/queue/enquiry' method='post' class='form-horizontal'>
	<div class="row">
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-4 control-label'><div align='left'>Location</div></label>
						<div class='col-sm-8'>
							{{ Form::select('locations', $locations, $selectedLocation, ['class'=>'form-control','maxlength'=>'10','onchange'=>'reload()']) }}
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label for='date_start' class='col-sm-3 control-label'><div align='left'>From</div></label>
						<div class='col-sm-9'>
							<div class="input-group date">
								<input data-mask="99/99/9999" name="date_start" id="date_start" type="text" class="form-control" value="{{ DojoUtility::dateReadFormat($date_start) }}">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							</div>
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label for='date_end' class='col-sm-3 control-label'>To</label>
						<div class='col-sm-9'>
							<div class="input-group date">
								<input data-mask="99/99/9999" name="date_end" id="date_end" type="text" class="form-control" value="{{ DojoUtility::dateReadFormat($date_end) }}">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							</div>
						</div>
					</div>
			</div>
	</div>
	<div class="row">
			<div class="col-xs-8">
					<div class='form-group'>
						<label for='date_end' class='col-sm-2 control-label'><div class='pull-left'>Consultant</div></label>
						<div class='col-sm-10'>
								{{ Form::select('user_id', $consultants,$user_id, ['class'=>'form-control','maxlength'=>'10']) }}
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<div class='col-sm-12'>
	<a href='#' onclick='javascript:search_now(0);' class='btn btn-primary'>Search</a>
	<a href='#' onclick='javascript:search_now(1);' class='btn btn-primary pull-right'><span class='fa fa-print'></span></a>
						</div>
					</div>
			</div>
	</div>
	<input type='hidden' id='export_report' name="export_report">
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
    <th>Consultant</th>
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
					{{strtoupper($queue->patient_name)}}
					<br>
					<small>{{$queue->patient_mrn}}</small>
			</td>
			<td>
					{{$queue->location_name}}
			</td>
			<td>
					{{$queue->name}}
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search) or isset($date_start) or isset($date_end)) 
	{{ $queues->appends(['search'=>$search, 'date_start'=>$date_start, 'date_end'=>$date_end])->render() }}
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
		$('#date_start').datepicker({
				format: "dd/mm/yyyy",
				todayBtn: "linked",
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true
		});

		$('#date_end').datepicker({
				format: "dd/mm/yyyy",
				todayBtn: "linked",
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true
		});
		function search_now(value) {
				document.getElementById('export_report').value = value;
				document.getElementById('form').submit();
		}
</script>
@endsection
