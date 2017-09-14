@extends('layouts.app')

@section('content')
<h1>Request Enquiry</h1>
<form id='form' action='/loan/enquiry' method='post' class='form-horizontal'>
	<div class="row">
			<div class="col-xs-4">
					<div class='form-group'>
						<label for='date_start' class='col-sm-3 control-label'><div align='left'>Product</div></label>
						<div class='col-sm-9'>
							<input type='text' class='form-control' placeholder="Name or code" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'><div align='left'>Ward</div></label>
						<div class='col-sm-9'>
							{{ Form::select('ward_code', $wards, $ward_code, ['class'=>'form-control','maxlength'=>'10']) }}
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label for='date_end' class='col-sm-3 control-label'>Type</label>
						<div class='col-sm-9'>
							{{ Form::select('type_code', $types, $type_code, ['class'=>'form-control','maxlength'=>'10']) }}
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
						<label for='date_end' class='col-sm-3 control-label'>Status</label>
						<div class='col-sm-9'>
							{{ Form::select('loan_code', $loan_status, $loan_code, ['class'=>'form-control','maxlength'=>'10']) }}
						</div>
					</div>
			</div>
	</div>
	<div class="row">
			<div class="col-xs-4">
			</div>
			<div class="col-xs-4">
			</div>
			<div class="col-xs-4">
			</div>
	</div>
	<a href='#' onclick='javascript:search_now(0);' class='btn btn-primary'>Search</a>
	<a href='#' onclick='javascript:search_now(1);' class='btn btn-primary pull-right'><span class='fa fa-print'></span></a>
	<input type='hidden' id='export_report' name="export_report">
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($loans->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Type</th> 
    <th>Name</th>
    <th>Code</th>
    <th>Quantity</th> 
    <th>Source</th> 
    <th>Request Date</th> 
    <th>Status</th> 
	</tr>
  </thead>
	<tbody>
@foreach ($loans as $loan)
	<tr>
			<td>
					{{ $loan->type_name }}
			</td>
			<td>
					{{$loan->product_name}}
					{{$loan->patient_name}}
			</td>
			<td>
					{{$loan->item_code}}
			</td>
			<td>
					{{$loan->loan_quantity}}
			</td>
			<td>
					{{$loan->ward_name}}
			</td>
			<td>
					{{ DojoUtility::dateTimeReadFormat($loan->request_date) }}
			</td>
			<td>
					{{$loan->loan_name}}
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $loans->appends(['search'=>$search])->render() }}
	@else
	{{ $loans->render() }}
@endif
<br>
@if ($loans->total()>0)
	{{ $loans->total() }} records found.
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
