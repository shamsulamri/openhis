@extends('layouts.app')

@section('content')
<h1>Discharge Enquiry</h1>
<form id='form' action='/discharge/enquiry' method='post' class='form-horizontal'>
	<div class="row">
			<div class="col-xs-4">
					<div class='form-group'>
						<label for='date_start' class='col-sm-3 control-label'><div align='left'>Patient</div></label>
						<div class='col-sm-9'>
							<input type='text' class='form-control' placeholder="Name or MRN" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label for='date_end' class='col-sm-3 control-label'>Encounter</label>
						<div class='col-sm-9'>
							{{ Form::select('encounter_code', $encounter_types, $encounter_code, ['class'=>'form-control','maxlength'=>'10']) }}
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label for='date_end' class='col-sm-3 control-label'>Outcome</label>
						<div class='col-sm-9'>
							{{ Form::select('outcome_code', $discharge_types, $outcome_code, ['class'=>'form-control','maxlength'=>'10']) }}
						</div>
					</div>
			</div>
	</div>
	<div class="row">
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
			<div class="col-xs-4">
					<div class='form-group'>
						<label for='date_end' class='col-sm-3 control-label'>Flag</label>
						<div class='col-sm-9'>
								{{ Form::select('flag_code', $flag,$flag_code, ['class'=>'form-control','maxlength'=>'1']) }}
						</div>
					</div>
			</div>
	</div>
	<div class="row">
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'><div align='left'>Type</div></label>
						<div class='col-sm-9'>
								{{ Form::select('type_code', $patient_types,$type_code, ['class'=>'form-control','maxlength'=>'1']) }}
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
					</div>
			</div>
	</div>

	<a href='#' onclick='javascript:search_now(0);' class='btn btn-primary'>Search</a>
	<a href='#' onclick='javascript:search_now(1);' class='btn btn-primary pull-right'><span class='fa fa-print'></span></a>
	<input type='hidden' id='export_report' name="export_report">
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if ($discharges->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Encounter</th>
    <th>Panel</th>
    <th>Encounter Date</th>
    <th>Discharge Date</th>
    <th>LOS/Waiting</th>
    <th>Name</th> 
    <th>Consultant</th> 
    <th>Outcome</th> 
	</tr>
  </thead>
	<tbody>
@foreach ($discharges as $discharge)
	<tr>
			<td>
					{{ $discharge->encounter_name }} ({{ $discharge->encounter_id }})
					<!--
					<br>
					<small>
					{{ $discharge->ward_name }}
					{{ $discharge->location_name }}
					</small>
					-->
			</td>
			<td>
					{{ $discharge->sponsor_name }}
			</td>
			<td>
					{{ DojoUtility::dateTimeReadFormat($discharge->encounter_date) }}
			</td>
			<td>
					{{ (DojoUtility::dateTimeReadFormat($discharge->discharge_date)) }}
			</td>
			<td>
					@if ($discharge->LOS>0)
							{{ abs($discharge->LOS) }} day
					@else
							{{ $discharge->waiting_time }} 
					@endif
			</td>
			<td>
					{{ strtoupper($discharge->patient_name) }}
					<br>
					<small>{{$discharge->patient_mrn}}</small>
			</td>
			<td>
					{{$discharge->name}}
			</td>
			<td>
					{{$discharge->type_name}}

			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>

{{ $discharges->appends(['search'=>$search, 
		'encounter_code'=>$encounter_code,
		'outcome_code'=>$outcome_code,
		'date_start'=>DojoUtility::dateReadFormat($date_start),
		'date_end'=>DojoUtility::dateReadFormat($date_end),
		'flag_code'=>$flag_code,
		'type_code'=>$type_code,
])->render() }}
<br>
@if ($discharges->total()>0)
	{{ $discharges->total() }} records found.
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
