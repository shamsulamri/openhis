@extends('layouts.app')

@section('content')
<h1>Bill Item Enquiry</h1>
<form id='form' action='/bill_item/enquiry' method='post' class='form-horizontal'>
	<div class="row">
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'><div align='left'>Find</div></label>
						<div class='col-sm-9'>
							<input type='text' class='form-control' placeholder="MRN or Encounter Id" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
						</div>
					</div>
			</div>
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
	</div>
	<div class="row">
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'>Category</label>
						<div class='col-sm-9'>
							{{ Form::select('category_code', $categories,$category_code, ['class'=>'form-control']) }}
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
					</div>
			</div>
	</div>
	<a href='#' onclick='javascript:search_now(0);' class='btn btn-primary'>Search</a>
	<a href='#' onclick='javascript:search_now(1);' class='btn btn-primary pull-right'><span class='fa fa-print'></span></a>
	<input type='hidden' id='export_report' name="export_report">
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if ($charges->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Patient</th>
    <th>Encounter</th>
    <th>Encounter Date</th>
    <th>Product</th>
    <th><div align='right'>Unit Pirce</div></th>
    <th>Quantity</th>
    <th><div align='right'>Total</div></th> 
	</tr>
  </thead>
	<tbody>
@foreach ($charges as $charge)
	<tr>
			<td>
					{{$charge->patient_name}}
					<br>
					<small>
					{{$charge->patient_mrn}}
					</small>
			</td>
			<td>
					{{$charge->encounter_id}}
			</td>
			<td>
					{{ (DojoUtility::dateReadFormat($charge->order_date)) }}
			</td>
			<td>
					{{$charge->product_name}}
					<br>
					<small>
					{{$charge->product_code}}
					</small>
			</td>
			<td align='right'>
					{{$charge->bill_unit_price}}
			</td>
			<td align='right'>
					{{$charge->bill_quantity}}
			</td>
			<td align='right'>
					{{number_format($charge->bill_amount,2)}}
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
{{ $charges->appends([
		'search'=>$search,
		'date_start'=>DojoUtility::dateReadFormat($date_start),
		'date_end'=>DojoUtility::dateReadFormat($date_end),
		'category_code'=>$category_code,
])->render() }}
<br>
@if ($charges->total()>0)
	{{ $charges->total() }} records found.
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
