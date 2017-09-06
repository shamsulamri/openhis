@extends('layouts.app')

@section('content')
<h1>Batch Number Enquiry</h1>
<form action='/stock_batch/search' method='post' class='form-inline'>
		<input type='text' class='form-control' placeholder="Batch Number" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<label>Status</label>
		{{ Form::select('status_code', $status, $status_code, ['class'=>'form-control']) }}
		<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
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
					{{$stock_batch->store->store_name}}
					<br>
					{{$stock_batch->stock->movement->move_name}}
			</td>
			<td>
					{{$stock_batch->product->product_name}}
					<br>
					{{ $stock_batch->product->product_code }}
			</td>
			<td>
					{{$stock_batch->total_quantity }}
			</td>
			<td>
						@if ($stock_batch->stock->purchaseOrderLine)
						{{ $stock_batch->stock->purchaseOrderLine->purchaseOrder->supplier->supplier_name }}
						@endif
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
@endsection
