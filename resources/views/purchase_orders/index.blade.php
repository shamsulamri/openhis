@extends('layouts.app')

@section('content')
<h1>Purchase Orders</h1>
<br>
<form action='/purchase_order/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/purchase_orders/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($purchase_orders->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Date</th>
    <th>Supplier</th> 
    <th>Status</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($purchase_orders as $purchase_order)
	<tr>
			<td>
					{{ date('d F Y', strtotime($purchase_order->purchase_date)) }}
			</td>
			<td>
					{{$purchase_order->supplier_name}}
			</td>
			<td>
					@if ($purchase_order->purchase_posted==1)
							@if ($purchase_order->purchase_received==1)
							<div class='label label-success'>
								Stock Receive
							</div>
							@else
							<div class='label label-warning'>
								Posted
							</div>
							@endif
					@else
					<div class='label label-default'>
						&nbsp;Open&nbsp;
					</div>
					@endif
			</td>
			<td align='right'>
					<a class='btn btn-default btn-xs' href='{{ URL::to('purchase_order_lines/'. $purchase_order->purchase_id) }}'>Line Items</a>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('purchase_orders/delete/'. $purchase_order->purchase_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $purchase_orders->appends(['search'=>$search])->render() }}
	@else
	{{ $purchase_orders->render() }}
@endif
<br>
@if ($purchase_orders->total()>0)
	{{ $purchase_orders->total() }} records found.
@else
	No record found.
@endif
@endsection
