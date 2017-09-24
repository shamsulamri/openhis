@extends('layouts.app')

@section('content')
<h1>Diet Enquiry</h1>

<form id='form' action='/admission/diet_enquiry' method='post' class='form-horizontal'>
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
							{{ Form::select('ward', $wards, $ward, ['class'=>'form-control','maxlength'=>'10']) }}
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'>Diet</label>
						<div class='col-sm-9'>
							{{ Form::select('diet_code', $diets, $diet_code, ['class'=>'form-control','maxlength'=>'10']) }}
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
    <th>Admission Date</th>
    <th>Patient</th>
    <th>Bed</th>
    <th>Diet</th>
    <th>Class</th>
    <th>Texture</th>
    <th>Description</th>
    <th>Alerts</th>
	</tr>
  </thead>
	<tbody>
@foreach ($admissions as $admission)
	<?php 
	$status='';
	?>
	<tr class='{{ $status }}'>
			<td>
					{{ DojoUtility::dateTimeReadFormat($admission->admission_date) }}
					<br>
					<small>
					<?php $ago =DojoUtility::diffForHumans($admission->admission_date); ?> 
					{{ $ago }}
					</small>	
			</td>
			<td>
					{{ strtoupper($admission->patient_name) }}
					<br>
					<small>{{$admission->patient_mrn}}</small>
			</td>
			<td>
					{{ $admission->bed_name }} 
						/ {{$admission->room_name}} 
					<br>
					<small>{{$admission->ward_name}}</small>	
			</td>
			<td>
					{{$admission->diet_name }}
			</td>
			<td>
					{{$admission->class_name }}
			</td>
			<td>
					{{$admission->texture_name }}
			</td>
			<td>
					{{$admission->diet_description }}
			</td>
			<td>
					{{$admission->alerts }}
			</td>
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
		function search_now(value) {
				document.getElementById('export_report').value = value;
				document.getElementById('form').submit();
		}
</script>
		
@endsection
