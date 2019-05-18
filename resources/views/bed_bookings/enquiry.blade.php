@extends('layouts.app')

@section('content')
<h1>Bed Reservation Enquiry</h1>
<form id='form' action='/preadmission/enquiry' method='post' class='form-horizontal'>
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
						<label for='date_end' class='col-sm-2 control-label'>Ward</label>
						<div class='col-sm-10'>
            				{{ Form::select('ward_code', $ward,$ward_code, ['class'=>'form-control','maxlength'=>'10']) }}
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label for='date_end' class='col-sm-2 control-label'>Class</label>
						<div class='col-sm-10'>
            				{{ form::select('class_code', $class,$class_code, ['class'=>'form-control','maxlength'=>'10']) }}
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
						<label for='date_end' class='col-sm-2 control-label'>To</label>
						<div class='col-sm-10'>
							<div class="input-group date">
								<input data-mask="99/99/9999" name="date_end" id="date_end" type="text" class="form-control" value="{{ DojoUtility::dateReadFormat($date_end) }}">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							</div>
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label for='date_end' class='col-sm-2 control-label'>Consultant</label>
						<div class='col-sm-10'>
								{{ Form::select('user_id', $consultants,$user_id, ['class'=>'form-control','maxlength'=>'10']) }}
						</div>
					</div>
			</div>
	</div>
	<a href='#' onclick='javascript:search_now(0);' class='btn btn-primary'>Search</a>
	<a href='#' onclick='javascript:search_now(1);' class='btn btn-primary pull-right'><span class='fa fa-print'></span></a>
	<input type='hidden' id='export_report' name="export_report">
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>

<br>
@if ($bed_bookings->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Date</th>
    <th>Patient</th>
    <th>MRN</th>
    <th>Ward</th>
    <th>Class</th>
	<th>Priority</th>
	</tr>
  </thead>
	<tbody>
@foreach ($bed_bookings as $bed_booking)
	<tr>
			<td>
				{{ DojoUtility::dateReadFormat($bed_booking->book_date) }}
			</td>
			<td>
					{{ $bed_booking->patient_name }}
			</td>
			<td>
					{{$bed_booking->patient_mrn }}
			</td>
			<td>
					{{$bed_booking->ward_name }}
			</td>
			<td>
					{{$bed_booking->class_name }} 
			</td>
			<td>
					{{$bed_booking->priority_name }} 
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
{{ $bed_bookings->appends(['search'=>$search])->render() }}
<br>
@if ($bed_bookings->total()>0)
	{{ $bed_bookings->total() }} records found.
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
