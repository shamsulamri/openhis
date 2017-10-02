@extends('layouts.app')

@section('content')
<h1>Bill Enquiry</h1>
<form id='form' action='/bill/enquiry' method='post' class='form-horizontal'>
	<div class="row">
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'><div align='left'>Find</div></label>
						<div class='col-sm-9'>
							<input type='text' class='form-control' placeholder="Name, MRN or Encounter Id" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'>User</label>
						<div class='col-sm-9'>
							{{ Form::select('user_id', $users,$user_id, ['class'=>'form-control']) }}
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'>Type</label>
						<div class='col-sm-9'>
							{{ Form::select('type_code', $patient_types,$type_code, ['class'=>'form-control']) }}
						</div>
					</div>
			</div>
	</div>
	<div class="row">
			<div class="col-xs-4">
					<div class='form-group'>
						<label  class='col-sm-3 control-label'><div align='left'>From</div></label>
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
						<label class='col-sm-3 control-label'>To</label>
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
						<label class='col-sm-3 control-label'>Sponsor</label>
						<div class='col-sm-9'>
							{{ Form::select('sponsor_code', $sponsor,$sponsor_code, ['class'=>'form-control']) }}
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
@if ($bills->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Patient</th> 
    <th>Encounter Id</th> 
    <th>Discharge Date</th> 
    <th>Sponsor</th> 
    <th>Posted By</th> 
    <th><div align='right'>Total</div></th>
    <th><div align='right'>Deposit</div></th>
    <th><div align='right'>Paid</div></th>
    <th><div align='right'>Outstanding</div></th>
    <th></th> 
	</tr>
  </thead>
	<tbody>
@foreach ($bills as $bill)
	<tr>
			<td>
					{{$bill->patient_name}}
					<br>
					<small>
					{{$bill->patient_mrn}}
					</small>
			</td>
			<td>
					{{ $bill->encounter_id }}
			</td>
			<td>
					{{ DojoUtility::dateReadFormat($bill->discharge_date) }}
			</td>
			<td>
					{{ $bill->sponsor_name?:"-" }}
			</td>
			<td>
					{{ $bill->name }}
			</td>
			<td>
					<div align='right'>
					{{ number_format($bill->bill_grand_total, 2) }}
					</div>
			</td>
			<td>
					<div align='right'>
					{{ number_format($bill->bill_deposit_total, 2) }}
					</div>
			</td>
			<td>
					<div align='right'>
					{{ number_format($bill->total_paid, 2) }}
					</div>
			</td>
			<td>
					<div align='right'>
					@if ($bill->bill_outstanding>0)
					<span class="label label-danger">
					{{ $bill->bill_outstanding }}
					</span>
					@else
					{{ $bill->bill_outstanding }}
					@endif
					</div>
			</td>
			<td>
				<a class='btn btn-default btn-sm pull-right' href='/bill_items/{{ $bill->encounter_id }}'>View</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $bills->appends(['search'=>$search])->render() }}
	@else
	{{ $bills->render() }}
@endif
<br>
@if ($bills->total()>0)
	{{ $bills->total() }} records found.
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
