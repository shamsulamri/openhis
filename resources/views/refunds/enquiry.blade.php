@extends('layouts.app')

@section('content')
<h1>Refund Enquiry</h1>

<form id='form' action='/refund/enquiry' method='post' class='form-horizontal'>
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
						<label class='col-sm-3 control-label'>From</label>
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
	</div>
	<div class="row">
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'><div align='left'>User</div></label>
						<div class='col-sm-9'>
							{{ Form::select('user_id', $users,$user_id, ['class'=>'form-control']) }}
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<div class='col-sm-12'>
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
@if ($refunds->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Patient</th>
    <th>Against</th>
    <th>Document Id</th> 
    <th>Date</th>
    <th>Description</th> 
    <th>User</th> 
    <th>Amount</th> 
	</tr>
  </thead>
	<tbody>
@foreach ($refunds as $refund)
	<tr>
			<td>
					{{$refund->patient_name}}
					<br>
					<small>
					{{$refund->patient_mrn}}
					</small>
			</td>
			<td>
					@if ($refund->refund_type==1)
						Bill
					@else
						Deposit
					@endif
			</td>
			<td>
					{{$refund->refund_reference}}
			</td>
			<td>
					{{ DojoUtility::dateTimeReadFormat($refund->created_at) }}
			</td>
			<td>
					{{$refund->refund_description?:"-"}}
			</td>
			<td>
					{{$refund->name}}
			</td>
			<td>
					{{ number_format($refund->refund_amount,2) }}
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $refunds->appends(['search'=>$search])->render() }}
	@else
	{{ $refunds->render() }}
@endif
<br>
@if ($refunds->total()>0)
	{{ $refunds->total() }} records found.
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
