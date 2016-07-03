@extends('layouts.app2')

@section('content')
@if (Session::has('message'))
	<br>
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
@if ($purchase_order->purchase_posted==1)
		@if (!Session::has('message'))
		<br>
		@endif
		@if ($purchase_order->purchase_received==1)
				<div class='alert alert-success'>
				Stock receive on <strong>{{ date('d F Y', strtotime(str_replace('/','-',$purchase_order->receive_datetime))) }}</strong> at <strong>{{ $purchase_order->store->store_name }}</strong>
				</div>
		@else
				<div class='alert alert-warning'>
				Purchase Order Posted
				</div>
		@endif
@endif
@if ($purchase_order->purchase_posted==1)
	@if ($purchase_order->purchase_received==0)
	<a href='/purchase_orders/{{ $purchase_id }}/edit' class='btn btn-default'>Stock Receive</a>
	@endif
@else
		@if (!Session::has('message'))
				<br>
		@endif
		<a href='/purchase_order/post?purchase_id={{ $purchase_id }}' class='btn btn-default'>Post Purchase Order</a>
@endif
	<a href='#' class='btn btn-default'><span class='glyphicon glyphicon-print'></span> Print</a>
	<br>
	<br>
<h5>
<strong>{{ $purchase_order->supplier->supplier_name }}</strong>
<br>
<small>
{{ $purchase_order->supplier->supplier_street_1 }}
<br>
{{ $purchase_order->supplier->supplier_street_2 }}
<br>
{{ $purchase_order->supplier->supplier_city }}
<br>
{{ $purchase_order->supplier->supplier_postcode }} {{ $purchase_order->supplier->supplier_city }}
<br>
{{ $purchase_order->supplier->supplier_state }}
</small>
<br>
<br>
<br>
<strong>Date: {{ date('d F Y', strtotime(str_replace('/','-',$purchase_order->purchase_date))) }}</strong>
</h5>
<br>
<?php 
$grandTotal=0.0; 
$count=0;
?>
@if ($purchase_order_lines->total()>0)
<table class="table table-condensed">
 <thead>
	<tr> 
    <th>#</th>
    <th>Product</th>
    <th><div align='right'>Quantity</div></th> 
    <th><div align='right'>Price/Unit</div></th> 
    <th><div align='right'>Total</div></th> 
	@if ($purchase_order->purchase_posted==0)
	<th></th>
	@endif
	</tr>
  </thead>
	<tbody>
@foreach ($purchase_order_lines as $purchase_order_line)
	<?php 
		$grandTotal += $purchase_order_line->line_total;
		$count += 1;
	?>
	<tr>
			<td width='10'>
					{{ $count }}	
			</td>
			<td>
					@if ($purchase_order->purchase_received==1)
						{{$purchase_order_line->product_name}}
					@else
					<a href='{{ URL::to('purchase_order_lines/'. $purchase_order_line->line_id . '/edit') }}'>
						{{$purchase_order_line->product_name}}
					</a>
					@endif
			</td>
			<td width='100' align='right'>
					{{ $purchase_order_line->line_quantity_received+$purchase_order_line->line_quantity_received_2 }}
			</td>
			<td width='50' align='right'>
					{{ number_format($purchase_order_line->line_price,2) }}
			</td>
			<td width='10' align='right'>
					{{ number_format($purchase_order_line->line_total,2) }}
			</td>
			@if ($purchase_order->purchase_posted==0)
			<td align='right' width='20'>
					@if ($purchase_order->purchase_received==0)
					<a class='btn btn-danger btn-xs' href='{{ URL::to('purchase_order_lines/delete/'. $purchase_order_line->line_id) }}'>-</a>
					@endif
			</td>
			@endif
	</tr>
@endforeach
@endif
@if ($grandTotal>0)
	<tr>
		<td></td>
		<td></td>
		<td width='50' align='right'></td>
		<td width='10' align='right'><br><strong>Total</strong></td>
		<td width='20' align='right'><br>{{ number_format($grandTotal,2) }}</td>
		@if ($purchase_order->purchase_posted==0)
		<td></td>
		@endif
	</tr>
@endif
</tbody>
</table>

@if (isset($search)) 
	{{ $purchase_order_lines->appends(['search'=>$search])->render() }}
	@else
	{{ $purchase_order_lines->render() }}
@endif
<br>
@if ($purchase_order_lines->total()>0)
	<!--
	Total: {{ $purchase_order_lines->total() }} items
	-->
@else
	No record found.
@endif

@endsection
