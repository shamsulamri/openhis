@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>
@if ($encounter->discharge)
Final Bill
@else
Interim Bill
@endif
</h1>
@if ($bills->total()>0)
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
@if ($encounter->discharge)
<br>
<a href='/bills/close/{{ $encounter_id }}' class='btn btn-default'>Financial Discharge</a>
@endif
<a href='/bills/reload/{{ $encounter_id }}' class='btn btn-warning pull-right'>Reload Bill</a>
<br>
<br>
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Item</th> 
    <th><div align='right'>Tax Code</div></th> 
    <th><div align='right'>Tax Rate</div></th> 
    <th><div align='right'>Quantity</div></th> 
    <th><div align='right'>Unit Price</div></th> 
    <th><div align='right'>Discount(%)</div></th> 
    <th><div align='right'>Total</div></th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
		@foreach ($bills as $bill)
			<tr>
					<td>
							<a href='{{ URL::to('bills/'. $bill->bill_id . '/edit') }}'>
							{{$bill->product_name}}
							</a>
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
							{{$bill->bill_quantity}}
					</td>
					<td align='right' width='100'>
							{{ number_format($bill->bill_unit_price,2) }}
					</td>
					<td align='right' width='100'>
						<?php if ($bill->bill_discount>0) { ?>
							{{ str_replace('.00','',number_format($bill->bill_discount,2)) }}
						<?php } ?>
							<?php if ($bill->bill_exempted==1) { ?>
								Exempted
							<?php } ?>
					</td>
					<td align='right' width='80'>
							{{ number_format($bill->bill_total,2) }}
					</td>
					<td align='right' width='80'>
							<a class='btn btn-danger btn-xs' href='{{ URL::to('bills/delete/'. $bill->bill_id) }}'>Delete</a>
					</td>
			</tr>
		@endforeach
	<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td align='right'>
					<strong>Grand Total</strong>
			</td>
			<td align='right'>
					<strong>{{number_format($bill_grand_total,2)}}<strong>
			</td>
			<td align='right'>
			</td>
	</tr>
</tbody>
</table>
<!-- GST Summary -->
	@if (count($gst_total)>0)
		<div class='row'>
			<div class='col-md-5'>
		<table class='table table-condensed'>
		 <thead>
			<tr> 
			<th>GST Summary</th> 
			<th><div align='right'>Amount (RM)</div></th> 
			<th><div align='right'>GST (RM)</div></th> 
			</tr>
		</thead>
		@foreach ($gst_total as $gst)
			@if ($gst->gst_sum>0)
			<tr>
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
			</tr>
			@endif
		@endforeach
		</table>
			</div>
		</div>
	@endif
@endif
<!-- Payments -->
<div class='panel panel-default'>
	<div class='panel-heading'>
<h4>Payments
<a href='/payments/create/{{ $bill->encounter_id }}' class='btn btn-primary pull-right'>New Payment</a>
</h4>
@if ($payments->total()>0)
<br>
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
					<strong>Deposits</strong>
				</div>
			</td>
			<td align='right' width='100'>
					<strong>{{number_format($deposit_total,2)}}<strong>
			</td>
			<td width='90'>
			</td>
	</tr>
	@if ($payment_total+$deposit_total-$bill_grand_total<0)
	<tr>
			<td>
			</td>
			<td>
				<div class='pull-right'>
					<strong>Outstanding</strong>
				</div>
			</td>
			<td align='right' width='100'>
					<strong>{{number_format($payment_total+$deposit_total-$bill_grand_total,2)}}<strong>
			</td>
			<td width='90'>
			</td>
	</tr>
	@else
	<tr>
			<td>
			</td>
			<td>
				<div class='pull-right'>
					<strong>Change</strong>
				</div>
			</td>
			<td align='right' width='100'>
					<strong>{{number_format($payment_total+$deposit_total-$bill_grand_total,2)}}<strong>
			</td>
			<td width='90'>
			</td>
	</tr>
	@endif
</table>
@else
	No payment collected.
@endif
</div>
</div>
@endsection
