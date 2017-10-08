@extends('layouts.app')

@section('content')
<h1>Batch Number Enquiry</h1>
<form id='form' action='/stock_batch/search' method='post' class='form-horizontal'>
	<div class="row">
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'><div align='left'>Batch</div></label>
						<div class='col-sm-9'>
							<input type='text' class='form-control' placeholder="Batch Number" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'>Status</label>
						<div class='col-sm-9'>
							{{ Form::select('status_code', $status, $status_code, ['class'=>'form-control']) }}
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'>Store</label>
						<div class='col-sm-9'>
							{{ Form::select('store', $store, $store_code, ['class'=>'form-control','maxlength'=>'10']) }}
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

@if (!empty($stock_batches))
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Batch</th>
    <th>Expiry</th> 
    <th>Store</th> 
    <th>Product</th> 
    <th>Quantity</th> 
    <th>Supplier</th> 
	@can('system-administrator')
	<th></th>
	@endcan
	</tr>
  </thead>
	<tbody>
@foreach ($stock_batches as $stock_batch)
	<tr>
			<td>
					{{$stock_batch->batch_number}}
			</td>
			<td>
					{{ DojoUtility::dateReadFormat($stock_batch->expiry_date)}}
			</td>
			<td>
					{{$stock_batch->store_name}}
					<br>
					{{$stock_batch->move_name}}
			</td>
			<td>
					{{$stock_batch->product_name}}
					<br>
					{{ $stock_batch->product_code }}
			</td>
			<td>
					{{$stock_batch->total_quantity }}
			</td>
			<td>
					{{ $stock_batch->supplier_name }}
			</td>
			@can('system-administrator')
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('stock_batches/delete/'. $stock_batch->batch_id) }}'>Delete</a>
			</td>
			@endcan
	</tr>
@endforeach
</tbody>
</table>
@if (isset($search) | isset($status_code)) 
	{{ $stock_batches->appends(['search'=>$search, 'status_code'=>$status_code])->render() }}
	@else
	{{ $stock_batches->render() }}
@endif
<br>
@if ($stock_batches->total()>0)
	{{ $stock_batches->total() }} records found.
@else
	No record found.
@endif
@endif
<script>
		function search_now(value) {
				document.getElementById('export_report').value = value;
				document.getElementById('form').submit();
		}
</script>
@endsection
