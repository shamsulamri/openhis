@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>Patient Bill</h1>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<br>
<a href='#' class='btn btn-default'>Print Tax Invoice</a>
<a href='#' class='btn btn-default'>Close Encounter</a>
<br>
<br>
@if ($bills->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Item</th> 
    <th><div align='right'>Tax Code</div></th> 
    <th><div align='right'>Tax Rate</div></th> 
    <th><div align='right'>Quantity</div></th> 
    <th><div align='right'>Unit Price</div></th> 
	<!--
    <th><div align='right'>Discount (%)</div></th> 
	-->
    <th><div align='right'>Total</div></th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($bills as $bill)
	<tr>
			<td>
					<a href='{{ URL::to('bills/'. $bill->order_id . '/edit') }}'>
					{{$bill->product_name}}
					</a>
					<?php if ($bill->order_exempted==1) { ?>
						<span class='label label-warning'>Exempted</span>
						&nbsp;
					<?php } ?>
			</td>
			<td align='right' width='100'>
					{{ $bill->tax_code }}
			</td>
			<td align='right' width='100'>
					{{ str_replace('.00','',$bill->tax_rate) }}
					<?php if ($bill->tax_rate) { ?>
					%
					<?php } ?>
			</td>
			<td align='right' width='80'>
					{{$bill->order_quantity_supply}}
			</td>
			<td align='right' width='100'>
					{{ number_format($bill->order_sale_price,2) }}
			</td>
			<!--
			<td align='right' width='100'>
				<?php if ($bill->order_discount>0) { ?>
					{{ number_format($bill->order_discount,2) }}
				<?php } ?>
			</td>
			-->
			<td align='right' width='80'>
					{{ number_format($bill->order_total,2) }}
			</td>
			<td align='right' width='80'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('bills/delete/'. $bill->order_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
	<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td align='right'>
					<strong>Total</strong>
			</td>
			<td align='right'>
					<strong>{{number_format($bill_total,2)}}<strong>
			</td>
			<td align='right'>
			</td>
	</tr>
@endif
</tbody>
</table>
<h4>
GST Summary
</h4>
<div class='row'>
	<div class='col-md-5'>
<table class='table table-condensed'>
 <thead>
	<tr> 
    <th>Tax Code</th> 
    <th><div align='right'>Amount (RM)</div></th> 
    <th><div align='right'>GST (RM)</div></th> 
	</tr>
  </thead>
	<tr>
@foreach ($gst_total as $gst)
	@if ($gst->gst_sum>0)
			<td>{{ $gst->tax_code}}</td>
			<td>
				<div class='pull-right'>
				{{ $gst->gst_amount }}
				</div>
			</td>
			<td>
				<div class='pull-right'>
				{{ $gst->gst_sum }}
				</div>
			</td>
	@endif
@endforeach
	</tr>
</table>
	</div>
</div>
<!--
<table width='100%'>
	<tr>
			<td>
				<div class='pull-right'>
					<strong>Discount (%)</strong>
				<br>
				<br>
				</div>
			</td>
			<td align='right' width='100'>
				-
				<br>
				<br>
			</td>
			<td width='105'>
			</td>
	</tr>
	<tr>
			<td>
				<div class='pull-right'>
					<strong>Grand Total</strong>
				</div>
			</td>
			<td align='right' width='100'>
					<strong>{{number_format($bill_total,2)}}<strong>
			</td>
			<td width='105'>
			</td>
	</tr>
</table>
-->
<!-- Payments -->
<div class='panel panel-default'>
	<div class='panel-heading'>
<h4>Payments Collection</h4>
<br>
<a href='/payments/create/{{ $bill->encounter_id }}' class='btn btn-primary'>New Collection</a>
<br>
<br>
@if ($payments->total()>0)
<table class="table table-hover">
	<tbody>
@foreach ($payments as $payment)
	<tr>
			<td>
					<a href='{{ URL::to('payments/'. $payment->payment_id . '/edit') }}'>
							{{$payment->payment_name}}
					</a>
			</td>
			<td>
					{{ $payment->payment_description }}
			</td>
			<td align='right' width='100'>
					{{ number_format($payment->payment_amount,2) }}
			</td>
			<td align='right' width='80'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('payments/delete/'. $payment->payment_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
	<tr>
			<td>
			</td>
			<td>
				<div class='pull-right'>
					<strong>Total Receive</strong>
				</div>
			</td>
			<td align='right'>
					<strong>{{number_format($payment_total,2)}}<strong>
			</td>
			<td align='right'>
			</td>
	</tr>
</tbody>
</table>
<table width='100%'>
	<tr>
			<td>
			</td>
			<td>
				<div class='pull-right'>
					<strong>Outstanding</strong>
				</div>
			</td>
			<td align='right' width='100'>
				@if ($payment_total-$bill_total<0)
					<strong>{{number_format($payment_total-$bill_total,2)}}<strong>
				@else
					-
				@endif
			</td>
			<td width='90'>
			</td>
	</tr>
	<tr>
			<td>
			</td>
			<td>
				<div class='pull-right'>
					<strong>Change</strong>
				</div>
			</td>
			<td align='right' width='100'>
				@if ($payment_total-$bill_total>0)
					<strong>{{number_format($payment_total-$bill_total,2)}}<strong>
				@else
					-
				@endif
			</td>
			<td width='90'>
			</td>
	</tr>
</table>
</div>
</div>
@endsection
