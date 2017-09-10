@extends('layouts.app')

@section('content')
<h1>Admission Enquiry</h1>

<form id='form' action='/admission/enquiry' method='post' class='form-inline'>
	<input type='text' class='form-control' placeholder="Patient name or MRN" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<label>Ward</label>
	{{ Form::select('ward', $wards, $ward, ['class'=>'form-control','maxlength'=>'10']) }}
	<label>Type</label>
	{{ Form::select('admission_code', $admission_type, $admission_code, ['class'=>'form-control','maxlength'=>'10']) }}
	<button class="btn btn-primary" type="submit" value="Submit">Search</button>
	<a href='#' onclick='javascript:export_report();' class='btn btn-primary pull-right'><span class='fa fa-print'></span></a>
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
    <th>Gender</th>
    <th>Bed</th>
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
					{{ $admission->gender_name }}
			</td>
			<td>
					{{ $admission->bed_name }} 
						/ {{$admission->room_name}} 
					<br>
					<small>{{$admission->ward_name}}</small>	
			</td>
			<td>
					{{$admission->name }}
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
		function export_report() {
				document.getElementById('export_report').value = "1";
				document.getElementById('form').submit();
		}
</script>
		
@endsection
