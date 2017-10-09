@extends('layouts.app')

@section('content')
<h1>Stock Movement Enquiry</h1>
<br>
<form id='form' action='/stock/search' method='post' class='form-horizontal'>
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
						<label for='date_end' class='col-sm-3 control-label'>Category</label>
						<div class='col-sm-9'>
							{{ Form::select('category_code', $categories, $category_code, ['class'=>'form-control','maxlength'=>'10']) }}
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label for='date_end' class='col-sm-3 control-label'>Store</label>
						<div class='col-sm-9'>
							{{ Form::select('store_code', $store, $store_code, ['class'=>'form-control','maxlength'=>'10']) }}
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
						<label for='date_end' class='col-sm-3 control-label'>Movement</label>
						<div class='col-sm-9'>
							{{ Form::select('move_code', $move, $move_code, ['class'=>'form-control','maxlength'=>'10']) }}
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


@if ($stocks->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Movement</th>
    <th>Date</th> 
    <th>Store</th> 
    <th>Product</th> 
    <th><div align='right'>Quantity</div></th> 
    <th><div align='right'>Value</div></th> 
	@can('system-administrator')
	<th></th>
	@endcan
	</tr>
  </thead>
	<tbody>
@foreach ($stocks as $stock)
	<tr>
			<td>
					{{ $stock->move_name }}
			</td>
			<td>
					{{ DojoUtility::dateTimeReadFormat($stock->stock_datetime)}}
			</td>
			<td>
					{{ $stock->store_from }}
					@if ($stock->store_to)
					<br>to
					{{ $stock->store_to }}
					@endif
			</td>
			<td>
					{{$stock->product->product_name}}
					<br>
					{{$stock->product->product_code}}
			</td>
			<td align='right'>
					@if ($stock->move_code=='adjust')
					{{ number_format($stock->stock_quantity) }}
					@else
					{{ number_format($stock->stock_quantity) }}
					@endif
			</td>
			<td align='right'>
					{{ number_format($stock->stock_value,2) }}
			</td>
			@can('system-administrator')
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('stocks/delete/'. $stock->stock_id) }}'>Delete</a>
			</td>
			@endcan
	</tr>
@endforeach
@endif
</tbody>
</table>

{{ $stocks->appends(['search'=>$search, 'store_code'=>$store_code, 'category_code'=>$category_code])->render() }}
<br>
@if ($stocks->total()>0)
	{{ $stocks->total() }} records found.
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
	</script>
@endsection
