@extends('layouts.app')

@section('content')
<h1>Appointment Enquiry</h1>
<form id='form' action='/appointment/enquiry' method='post' class='form-horizontal'>
	<div class="row">
			<div class="col-xs-4">
					<div class='form-group'>
						<label for='date_start' class='col-sm-3 control-label'><div align='left'>MRN</div></label>
						<div class='col-sm-9'>
							<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label for='date_end' class='col-sm-2 control-label'>Service</label>
						<div class='col-sm-10'>
							{{ Form::select('services', $services, $service, ['class'=>'form-control']) }}
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label for='date_end' class='col-sm-2 control-label'>Status</label>
						<div class='col-sm-10'>
							{{ Form::select('status_code', $appointment_status, $status_code, ['class'=>'form-control']) }}
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
	<a href='#' onclick='javascript:search_now(0);' class='btn btn-primary'>Search</a>
	<a href='#' onclick='javascript:search_now(1);' class='btn btn-primary pull-right'><span class='fa fa-print'></span></a>
	<input type='hidden' id='export_report' name="export_report">
			</div>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>

<br>
@if ($appointments->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Slot Date</th>
    <th>Service</th>
    <th>Patient</th>
    <th>Home Phone</th> 
    <th>Mobile Phone</th> 
    <th>Note</th> 
	</tr>
  </thead>
	<tbody>
@foreach ($appointments as $appointment)
	<tr>
			<td>
					{{ DojoUtility::dateTimeReadFormat($appointment->appointment_datetime) }}
			</td>
			<td>
					{{$appointment->service_name}}
			</td>
			<td>
					{{$appointment->patient_name}}
					<br>
					<small>{{$appointment->patient_mrn}}</small>
			</td>
			<td>
				{{ $appointment->patient_phone_home }}
			</td>
			<td>
				{{ $appointment->patient_phone_mobile }}
			</td>
			<td>
				{{ $appointment->appointment_cancel }}
				{{ $appointment->appointment_description }}
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
{{ $appointments->appends(['search'=>$search, 'service'=>$service, 'date_start'=>$date_start, 'date_end'=>$date_end])->render() }}
<br>
@if ($appointments->total()>0)
	{{ $appointments->total() }} records found.
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
