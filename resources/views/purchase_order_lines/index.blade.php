@extends('layouts.app2')

@section('content')
<?php 
$grandTotal=0.0; 
$count=0;
?>
@if ($purchase_order->purchase_posted==1)
		@if ($purchase_order->purchase_received==1)
				<div class='alert alert-success'>
				Stock receive on <strong>{{ (DojoUtility::dateLongFormat(str_replace('/','-',$purchase_order->receive_datetime))) }}</strong> at <strong>{{ $purchase_order->store->store_name }}</strong>
				</div>
		@else
				<div class='alert alert-warning'>
				Purchase Order Posted
				</div>
		@endif
@endif
@if ($purchase_order->purchase_posted==1)
	@if ($purchase_order->purchase_received==0)
	<a href='/purchase_orders/{{ $purchase_id }}/edit' class='btn btn-primary'>Stock Receive</a>
	@else
	<a href='/purchase_orders/{{ $purchase_id }}/edit' class='btn btn-default'>Update Invoice Information</a>
	@endif
@else
		@can('module-diet')
		<a href='/purchase_order/diet/{{ $purchase_id }}' class='btn btn-primary'>Diet BOM</a>
		@endcan
@endif
@foreach ($purchase_order_lines as $purchase_order_line)
	<?php 
		if (empty($purchase_order_line->deleted_at)) {
			$grandTotal += $purchase_order_line->line_total;
		}
	?>
@endforeach
@if ($grandTotal>0 && $purchase_order->purchase_posted==0)
		<a href='/purchase_order/post?purchase_id={{ $purchase_id }}' class='btn btn-primary'>Post Purchase Order</a>
@endif
		<a class="btn btn-warning pull-right" target="_blank" href="{{ Config::get('host.report_server') }}/ReportServlet?report=purchase_order&id={{ $purchase_id }}" role="button">Print</a> 
	<br>
	<br>
	<div class="row">
			<div class="col-xs-6">
				From:
				<address>
				<strong>{{ $purchase_order->supplier->supplier_name }}</strong><br>
				{{ $purchase_order->supplier->supplier_street_1 }}
				<br>
				{{ $purchase_order->supplier->supplier_street_2 }}
				<br>
				{{ $purchase_order->supplier->supplier_city }}
				<br>
				{{ $purchase_order->supplier->supplier_postcode }} {{ $purchase_order->supplier->supplier_city }}
				<br>
				@if (!empty($pruchase_order->supplier->sate))
				{{ $purchase_order->supplier->state->state_name }}
				@endif
				</address>
			</div>
			<div class="col-xs-6">
				<div class='text-right'>
				@if ($purchase_order->purchase_received==1)
						<h4>Invoice No.</h4>
						<h4 class='text-navy'>{{ $purchase_order->invoice_number }}</h4>
						<span><strong>Invoice Date:</strong> {{ date('d/m/Y', strtotime(str_replace('/','-',$purchase_order->invoice_date))) }}</span><br>
				@else	
				<strong>Purchase ID:</strong> {{ $purchase_order->purchase_id }}<br>
				<strong>Purchase Date:</strong> {{ date('d/m/Y', strtotime(str_replace('/','-',$purchase_order->purchase_date))) }}
				@endif
				</div>
			</div>
	</div>

<br>
<br>
@if ($purchase_order_lines->total()>0)
<table class="table table-condensed">
 <thead>
	<tr> 
    <th>#</th>
    <th>Product</th>
    <th><div align='right'>Quantity</div></th> 
    <th><div align='right'>Price/Unit</div></th> 
    <th><div align='right'>Total</div></th> 
	@if ($purchase_order->purchase_received==0)
	<th></th>
	@endif
	</tr>
  </thead>
	<tbody>
@foreach ($purchase_order_lines as $purchase_order_line)
	<?php 
		$count += 1;
	?>
	<tr>
			<td width='10'>
					{{ $count }}	
			</td>
			<td>
			@if (empty($purchase_order_line->deleted_at))
					@if ($purchase_order->purchase_received==1)
						{{$purchase_order_line->product->product_name}}
					@else
					<a href='{{ URL::to('purchase_order_lines/'. $purchase_order_line->line_id . '/edit') }}'>
						{{$purchase_order_line->product->product_name}}
					</a>
					@endif
			@else
					<s>
						{{$purchase_order_line->product->product_name}}
					</s>
			@endif
			</td>
			<td width='100' align='right'>
					{{ $purchase_order_line->line_quantity_received+$purchase_order_line->line_quantity_received_2 }} 
					{{ $purchase_order_line->product->getUnitShortname() }}
			</td>
			<td width='50' align='right'>
					{{ number_format($purchase_order_line->line_price,2) }}
			</td>
			<td width='10' align='right'>
					{{ number_format($purchase_order_line->line_total,2) }}
			</td>
			<td align='right' width='20'>
					@if ($purchase_order->purchase_received==0)
					@if (empty($purchase_order_line->deleted_at))
					<a class='btn btn-danger btn-xs' href='{{ URL::to('purchase_order_lines/delete/'. $purchase_order_line->line_id) }}'>-</a>
					@endif
					@endif
			</td>
	</tr>
@endforeach
@endif
@if ($grandTotal>0)
	<tr>
		<td colspan='4' align='right'><br><strong>Grand Total</strong></td>
		<td width='20' align='right'><br>{{ number_format($grandTotal,2) }}</td>
		<td></td>
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
