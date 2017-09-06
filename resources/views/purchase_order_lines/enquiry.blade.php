@extends('layouts.app')

@section('content')
<h1>Purchase Enquiry</h1>
<form action='/purchase_order_lines/enquiry' method='post' class='form-horizontal'>
	<div class="row">
			<div class="col-xs-4">
					<div class='form-group'>
						<label for='date_start' class='col-sm-3 control-label'><div align='left'>Number</div></label>
						<div class='col-sm-9'>
							<input type='text' class='form-control' placeholder="Order or invoice number" name='document_number' value='{{ $document_number }}'' }}' autocomplete='off' autofocus>
						</div>
					</div>
			</div>
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
						<label for='date_end' class='col-sm-3 control-label'>Status</label>
						<div class='col-sm-9'>
							{{ Form::select('status_code', $order_status, $status_code, ['class'=>'form-control','maxlength'=>'10']) }}
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
	<button class="btn btn-primary" type="submit" value="Submit">Search</button>
			</div>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($lines->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th width='100'>PO Number</th> 
    <th width='100'>Order Date</th> 
    <th width='100'>Delivery Date</th> 
    <th width='100'>Invoice Number</th> 
    <th>Product</th>
    <th>Code</th>
    <th>Status</th>
    <th width='100'><div align='right'>Quantity</div></th> 
    <th width='100'><div align='right'>Value</div></th> 
	@can('system-administrator')
	<th></th>
	@endcan
	</tr>
  </thead>
	<tbody>
@foreach ($lines as $line)
	<tr>
			<td>
					{{$line->stockInput->purchase->purchase_order_number}}
			</td>
			<td>
					{{ DojoUtility::dateReadFormat($line->stockInput->purchase->purchase_date) }}
			</td>
			<td>
					{{ DojoUtility::dateReadFormat($line->created_at) }}
			</td>
			<td>
					{{ $line->invoice_number }}	
			</td>
			<td>
					{{$line->product->product_name}}
			</td>
			<td>
					{{$line->product->product_code}}
			</td>
			<td>
				<?php
					if ($line->purchase_posted==1 & $line->purchase_received==0) echo '<span class="label label-success">Posted</span>';
					if ($line->purchase_posted==1 & $line->purchase_received==1) echo '<span class="label label-default">Close</span>';
					if ($line->purchase_posted==0 & $line->purchase_received==0) echo '<span class="label label-success">Open</span>';
				?>
			</td>
			<td align='right'>
					{{$line->line_quantity}}
			</td>
			<td align='right'>
					{{$line->line_value}}
			</td>
			@can('system-administrator')
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('purchase_order_lines/delete/'. $line->line_id) }}'>Delete</a>
			</td>
			@endcan
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $lines->appends(['search'=>$search])->render() }}
	@else
	{{ $lines->render() }}
@endif
<br>
@if ($lines->total()>0)
	{{ $lines->total() }} records found.
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
	</script>
@endsection
