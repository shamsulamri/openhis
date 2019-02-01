@extends('layouts.app')

@section('content')
<h1>Stock Movements Enquiry</h1>
<form id='form' action='/inventory/enquiry' method='post' class='form-horizontal'>
	<div class="row">
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'><div align='left'>Product</div></label>
						<div class='col-sm-9'>
							<input type='text' class='form-control' placeholder="Name or code" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
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
	<a href='#' onclick='javascript:search_now(0);' class='btn btn-primary'>Search</a>
	<a href='#' onclick='javascript:search_now(1);' class='btn btn-primary pull-right'><span class='fa fa-print'></span></a>
	<input type='hidden' id='export_report' name="export_report">
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($inventories->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Date</th> 
    <th>Movement</th>
    <th>Code</th>
    <th>Product</th>
    <th>Store</th>
    <th>Batch</th>
    <th><div align='right'>Quantity</div></th> 
	</tr>
  </thead>
	<tbody>
@foreach ($inventories as $inventory)
	<tr>
			<td>
					{{ DojoUtility::dateReadFormat($inventory->inv_datetime) }}
			</td>
			<td>
				{{ $inventory->move_name }}
				@if (!empty($inventory->move_description))
				 ({{ $inventory->move_description }})
				@endif
			</td>
			<td>
				{{ $inventory->product_code }}
			</td>
			<td>
				{{ $inventory->product->product_name }}
			</td>
			<td>
				{{ $inventory->store_name }}
			</td>
			<td>
					{{ $inventory->inv_batch_number?:'-' }}
			</td>
			<td align='right'>
					{{ $inventory->inv_quantity }}
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $inventories->appends(['search'=>$search])->render() }}
	@else
	{{ $inventories->render() }}
@endif
<br>
@if ($inventories->total()>0)
	{{ $inventories->total() }} records found.
@else
	No record found.
@endif
<script>
		function search_now(value) {
				document.getElementById('export_report').value = value;
				document.getElementById('form').submit();
		}
</script>
@endsection
