@extends('layouts.app')

@section('content')
<h1>Back Order Enquiry 
<a href='/purchase_lines/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form id='form' action='/purchase_line/backorder' method='post' class='form-horizontal'>
	<div class="row">
			<div class="col-xs-4">
					<div class='form-group'>
						<label for='date_start' class='col-sm-3 control-label'><div align='left'>Number</div></label>
						<div class='col-sm-9'>
							<input type='text' class='form-control' placeholder="Document number" name='document_number' value='{{ $document_number }}'' }}' autocomplete='off' autofocus>
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label for='date_end' class='col-sm-3 control-label'>Supplier</label>
						<div class='col-sm-9'>
							{{ Form::select('supplier_code', $supplier,$supplier_code, ['class'=>'form-control','maxlength'=>'20']) }}
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label for='date_start' class='col-sm-3 control-label'><div align='left'>Product</div></label>
						<div class='col-sm-9'>
							<input type='text' class='form-control' placeholder="Name or code" name='product_name' value='{{ isset($product_name) ? $product_name : '' }}' autocomplete='off' autofocus>
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
						<label class='col-sm-3 control-label'>Store</label>
						<div class='col-sm-9'>
							{{ Form::select('store_code', $store, $store_code, ['class'=>'form-control','maxlength'=>'10']) }}
						</div>
					</div>
			</div>
	</div>

	<div class="row">
			<div class="col-xs-4">
					<div class='form-group'>
						<label for='date_end' class='col-sm-3 control-label'>Document</label>
						<div class='col-sm-9'>
							{{ Form::select('document_code', $documents,$document_code?:'', ['class'=>'form-control','maxlength'=>'20']) }}
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

@if ($purchase_lines->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Document</th> 
    <th>Date</th> 
    <th>Supplier/Store</th> 
    <th>Product</th>
    <th>Original<br>Quantity</th>
    <th>Outstanding<br>Quantity</th>
    <th>Unit</th>
    <th>Price</th>
	</tr>
  </thead>
	<tbody>
@foreach ($purchase_lines as $purchase_line)
	<tr>
			<td>
					{{$purchase_line->purchase_number}}
			</td>
			<td>
					{{ DojoUtility::dateReadFormat($purchase_line->purchase_date) }}
			</td>
			<td>
					@if ($purchase_line->document_code=='purchase_request')
					{{$purchase_line->supplier_name}}
					@else
					{{$purchase_line->store_name}}
					@endif
			</td>
			<td>
					{{$purchase_line->product_name}}
			</td>
			<td>
					{{$purchase_line->line_quantity }}
			</td>
			<td>
					{{$purchase_line->outstanding_quantity }}
			</td>
			<td>
					{{$purchase_line->unit_name }}
			</td>
			<td>
					{{ number_format($purchase_line->line_unit_price,2) }}
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>

{{ $purchase_lines->appends(['product_name'=>$product_name,
					'document_number'=>$document_number,
					'supplier_code'=>$supplier_code,
					'date_start'=>$date_start,
					'date_end'=>$date_end,
					'store_code'=>$store_code,
					'document_code'=>$document_code
 ])->render() }}
<br>
@if ($purchase_lines->total()>0)
	{{ $purchase_lines->total() }} records found.
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

		$('.clockpicker').clockpicker();

		function search_now(value) {
				document.getElementById('export_report').value = value;
				document.getElementById('form').submit();
		}
</script>
@endsection
