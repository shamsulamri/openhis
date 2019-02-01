@extends('layouts.app')

@section('content')
<h1>Purchase Enquiry 
<a href='/purchase_lines/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form id='form' action='/purchase_line/enquiry' method='post' class='form-horizontal'>
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
						<label for='date_end' class='col-sm-3 control-label'>Status</label>
						<div class='col-sm-9'>
							{{ Form::select('status_code', $order_status, $status_code, ['class'=>'form-control','maxlength'=>'10']) }}
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
    <th>Supplier</th> 
    <th>Product</th>
    <th>Quantity</th>
    <th>Unit Price</th>
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($purchase_lines as $purchase_line)
	<tr>
			<td>
					{{$purchase_line->purchase->purchase_number}}
			</td>
			<td>
					{{ DojoUtility::dateReadFormat($purchase_line->purchase->created_at) }}
			</td>
			<td>
					{{$purchase_line->purchase->supplier->supplier_name}}
			</td>
			<td>
					{{$purchase_line->product->product_name}}
					<br>
					{{$purchase_line->product_code}}
			</td>
			<td>
					{{$purchase_line->line_quantity }}
					{{ $purchase_line->unit_name }}
			</td>
			<td>
					{{ number_format($purchase_line->line_unit_price,2) }}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('purchase_lines/delete/'. $purchase_line->line_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($product_name)) 
	{{ $purchase_lines->appends(['product_name'=>$product_name])->render() }}
	@else
	{{ $purchase_lines->render() }}
@endif
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
