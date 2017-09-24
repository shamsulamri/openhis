@extends('layouts.app')

@section('content')
<h1>Payment Enquiry</h1>

<form id='form' action='/payment/enquiry' method='post' class='form-horizontal'>
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
@if ($payments->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Patient</th>
    <th>Encounter Id</th>
    <th>Date</th>
    <th>Amount</th> 
    <th>Method</th> 
    <th>User</th> 
    <th>Description</th> 
	</tr>
  </thead>
	<tbody>
@foreach ($payments as $payment)
	<tr>
			<td>
					{{$payment->patient_name}}
					<br>
					<small>
					{{$payment->patient_mrn}}
					</small>
			</td>
			<td>
					{{$payment->encounter_id}}
			</td>
			<td>
					{{ DojoUtility::dateTimeReadFormat($payment->created_at) }}
			</td>
			<td>
					{{ number_format($payment->payment_amount,2) }}
			</td>
			<td>
					{{$payment->payment_name}}
			</td>
			<td>
					{{$payment->name}}
			</td>
			<td>
					{{$payment->payment_description?:"-"}}
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $payments->appends(['search'=>$search])->render() }}
	@else
	{{ $payments->render() }}
@endif
<br>
@if ($payments->total()>0)
	{{ $payments->total() }} records found.
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
