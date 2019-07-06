@extends('layouts.app')

@section('content')
<h1>Admission Enquiry</h1>

<form id='form' action='/admission/enquiry' method='post' class='form-horizontal'>


	<div class="row">
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'><div align='left'>Patient</div></label>
						<div class='col-sm-9'>
							<input type='text' class='form-control' placeholder="Patient name or MRN" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'>Ward</label>
						<div class='col-sm-9'>
							{{ Form::select('ward', $wards, $ward_code, ['class'=>'form-control','maxlength'=>'10']) }}
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'><div align='left'>Type</div></label>
						<div class='col-sm-9'>
							{{ Form::select('admission_code', $admission_type, $admission_code, ['class'=>'form-control','maxlength'=>'10']) }}
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
	</div>
	<a href='#' onclick='javascript:search_now(0);' class='btn btn-primary'>Search</a>
	<a href='#' onclick='javascript:search_now(1);' class='btn btn-primary pull-right'><span class='fa fa-print'></span></a>
	<input type='hidden' id='export_report' name="export_report" value="0">
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($admissions->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Encounter Id</th>
    <th>Admission Date</th>
    <th>Bed</th>
    <th>Patient</th>
    <th>Gender</th>
    <th>Consultant</th>
	</tr>
  </thead>
	<tbody>
@foreach ($admissions as $admission)
	<?php 
	$status='';
	?>
	<tr class='{{ $status }}'>
			<td>
					{{ $admission->encounter_id }}
			</td>
			<td>
					{{ DojoUtility::dateTimeReadFormat($admission->admission_date) }}
					<br>
					<small>
					<?php $ago =DojoUtility::diffForHumans($admission->admission_date); ?> 
					{{ $ago }}
					</small>	
			</td>
			<td>
					{{ $admission->bed_name }} 
						/ {{$admission->room_name}} 
					<br>
					<small>{{$admission->ward_name}}</small>	
			</td>
			<td>
					{{ strtoupper($admission->patient_name) }}
					<br>
					<small>{{$admission->patient_mrn}}</small>
			</td>
			<td>
					{{ $admission->gender_name }}
			</td>
			<td>
					{{$admission->name }}
			</td>
			@if (Auth::user()->authorization->view_progress_note==1)
			<td>
				<a class='btn btn-default btn-sm pull-right' href='/admission/progress/{{ $admission->patient_id }}'>Progress</a>
			</td>
			@endif
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
