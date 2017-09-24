@extends('layouts.app')

@section('content')
<h1>Deposit List</h1>
<form id='form' action='/deposit/enquiry' method='post' class='form-horizontal'>
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
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'>Method</label>
						<div class='col-sm-9'>
							{{ Form::select('payment_code', $payment_methods,$payment_code, ['id'=>'payment_code','onchange'=>'paymentChanged()','class'=>'form-control']) }}
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
<br>
@if ($deposits->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Patient</th> 
    <th>Encounter Id</th>
    <th>Deposit Date</th> 
    <th>Payment Method</th>
    <th><div align='right'>Deposit</div></th> 
    <th><div align='right'>Current Charges</div></th> 
	</tr>
  </thead>
	<tbody>
@foreach ($deposits as $deposit)
	<tr>
			<td>
					{{$deposit->patient_name}}
					<br>
					<small>
					{{$deposit->patient_mrn}}
					</small>
			</td>
			<td>
					{{$deposit->encounter_id}}
			</td>
			<td>
					{{ (DojoUtility::dateTimeReadFormat($deposit->deposit_date)) }}
			</td>
			<td>
					{{$deposit->payment_name}}
			</td>
			<td align='right'>
					{{ number_format($deposit->total_deposit,2) }}
			</td>
			<td align='right'>
					@if ($deposit->current_charges>$deposit->total_deposit)
					<span class="label label-danger">
					{{ number_format($deposit->current_charges, 2) }}
					</span>
					@else
					{{ number_format($deposit->current_charges, 2) }}
					@endif
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $deposits->appends(['search'=>$search])->render() }}
	@else
	{{ $deposits->render() }}
@endif
<br>
@if ($deposits->total()>0)
	{{ $deposits->total() }} records found.
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
