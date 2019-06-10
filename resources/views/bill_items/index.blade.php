@extends('layouts.app')

@section('content')
@include('patients.id_only')
<h1>
@if ($encounter->discharge)
Final Bill
@else
Interim Bill
@endif
{{ $bill_label }}
</h1>
<h3>
Encounter date: {{ date('d F Y, H:i', strtotime($encounter->created_at)) }}
</h3>
@if ($encounter->queue)
<h3>
{{ $encounter->queue->location->location_name }}
</h3>
@endif
@if (!$encounter->discharge && !$billPosted)
	<div class='alert alert-warning'>
	Click Reload button to compile latest bill items.
	</div>
@endif

@if (!empty($encounter->sponsor_code))
	@if ($non_claimable_amount>0 && $claimable_amount>0 && count($encounter->bill)==1)
			<div class='alert alert-danger'>
			You have unposted bill.
			</div>
	@endif
@endif

@if ($encounter->discharge)
	@if (!$billPosted)
		@if ($incomplete_orders>0 && $encounter->discharge->discharge_id)
		<div class='alert alert-danger' role='alert'>Patient has {{ $incomplete_orders }} incomplete order(s)
<a href='/order/enquiry?encounter_id={{ $encounter->encounter_id }}&status_code=incomplete' class='pull-right'>Show incomplete orders</a>
</div>
		@else
		<br>
		@endif
	@endif
@endif

@if (!empty($encounter->sponsor_code))
		@if ($claimable_amount>0)
			<a class="btn btn-primary" href="/bill_items/{{ $encounter->encounter_id }}/false" role="button">Claimable ({{ $claimable_amount }})</a>
		@endif
		@if ($non_claimable_amount>0)
			<a class="btn btn-primary" href="/bill_items/{{ $encounter->encounter_id }}/true" role="button">Non Claimable ({{ $non_claimable_amount }})</a>
		@endif
@endif
@if (!$billPosted)
<a href='/bill_items/reload/{{ $encounter_id }}' class='btn btn-warning pull-right'>Reload Bill</a>
<p class='pull-right'>&nbsp;</p>
<a class="btn btn-default pull-right" href="{{ Config::get('host.report_server') }}/ReportServlet?report=bill&id={{ $encounter->encounter_id }}&billNonClaimable={{ $non_claimable }}" role="button" target="_blank">Print Interim Bill</a> 
<p class='pull-right'>&nbsp;</p>
<!--
<a class="btn btn-default pull-right" href="{{ Config::get('host.report_server') }}/ReportServlet?report=bill_simple&id={{ $encounter->encounter_id }}&billNonClaimable={{ $non_claimable }}" role="button" target="_blank">Print Simple Invoice</a> 
-->
@else
<p class='pull-right'>&nbsp;</p>
<a class="btn btn-default pull-right" href="{{ Config::get('host.report_server') }}/ReportServlet?report=bill_receipt&id={{ $encounter->encounter_id }}&billNonClaimable={{ $non_claimable }}" role="button" target="_blank">Print Receipt</a> 
<p class='pull-right'>&nbsp;</p>
<a class="btn btn-default pull-right" href="{{ Config::get('host.report_server') }}/ReportServlet?report=bill&id={{ $encounter->encounter_id }}&billNonClaimable={{ $non_claimable }}" role="button" target="_blank">Print Invoice</a> 
<p class='pull-right'>&nbsp;</p>
<!--
<a class="btn btn-default pull-right" href="{{ Config::get('host.report_server') }}/ReportServlet?report=bill_simple&id={{ $encounter->encounter_id }}&billNonClaimable={{ $non_claimable }}" role="button" target="_blank">Print Simple Invoice</a> 
-->
@endif
@if ($hasMc) 
<p class='pull-right'>&nbsp;</p>
<a class="btn btn-default pull-right" href="{{ Config::get('host.report_server') }}/ReportServlet?report=medical_certificate&id={{ $encounter->encounter_id }}" role="button" target="_blank">Print Medical Certificate</a>
@endif
@if (empty($encounter->sponsor_code))
<br>
<br>
@endif
<!-- Bill Method -->
@if (!empty($encounter->sponsor))
	<br>
@endif

@if (!$billPosted)
		<div class="widget style1 gray-bg">
		<h4>Billing Method</h4>
		@if ($encounter->sponsor)
			<h4>
			<strong>
			<a href='/bill/bill_edit/{{ $encounter->encounter_id }}'>
			{{ $encounter->sponsor->sponsor_name }}
			</a>
			</strong>
			</h4>
			Membership Number: {{ $encounter->sponsor_id}}
			@else
			<a href='/bill/bill_edit/{{ $encounter->encounter_id }}'>Public</a>
		@endif
		</div>
@endif

@if ($bills->total()>0)
<div class="widget style1 gray-bg">
<table class="table table-condensed">
 <thead>
	<tr> 
    <th>Code</th> 
    <th>Item</th> 
    <th><div align='right'>Discount</div></th> 
    <th><div align='right'>Quantity</div></th> 
    <th><div align='right'>Unit Price</div></th> 
    <th><div align='right'>Subtotal</div></th> 
    <th><div align='right'>Tax</div></th> 
    <th><div align='right'>Subtotal Include Tax</div></th> 
	@can('system-administrator')	
	<th></th>
	@endcan
	</tr>
</thead>
	<tbody>
<?php $category=""; ?>
		@foreach ($bills as $bill)
@if ($category != $bill->category_name)
<tr>
	<td colspan=9>
<strong>
{{ $bill->category_name }}
</strong>
	</td>
</tr>
<?php $category = $bill->category_name; ?>
@endif
			<tr>
					<td width='100'>
							@if ($bill->product_non_claimable)
								<span class='label label-warning'>
							@endif
						{{ $bill->product_code }}
							@if ($bill->product_non_claimable)
								</span>
							@endif
					</td>
					<td>
							@if (!$billPosted)
							<a href='{{ URL::to('bill_items/'. $bill->bill_id . '/edit') }}'>
							@endif
							{{ strtoupper($bill->product_name) }}
							@if (!$billPosted)
							</a>
							@endif
					</td>
					<td align='right' width='50'>
						<?php if ($bill->bill_discount>0) { ?>
							{{ floatval($bill->bill_discount) }} %
						<?php } ?>
							<?php if ($bill->bill_exempted==1) { ?>
								Exempted
							<?php } ?>
					</td>
					<td align='right' width='100'>
							{{$bill->bill_quantity}} {{$bill->unit_name?:"Unit"}}
					</td>
					<td align='right' width='100'>
							{{ number_format($bill->bill_unit_price,2) }}
					</td>
					<td align='right' width='80'>
							{{ number_format($bill->bill_amount_exclude_tax,2) }}
					</td>
					<td align='right' width='50'>
							{{ $bill->tax_code }}
					</td>
					<td align='right' width='80'>
							{{ number_format($bill->bill_amount,2) }}
					</td>
					@can('system-administrator')
					<td align='right' width='80'>
						@if (!$billPosted)
							<a class='btn btn-danger btn-xs' href='{{ URL::to('bill_items/delete/'. $bill->bill_id) }}'>Delete</a>
						@endif
					</td>
					@endcan
			</tr>
		@endforeach
	<tr>
			<td colspan=7 align='right'>
					<strong>Total</strong>
			</td>
			<td align='right'>
					<strong>{{ number_format($bill_total,2) }}<strong>
			</td>
			@can('system-administrator')
			<td align='right'>
			</td>
			@endcan
	</tr>
	<tr>
			<td colspan=7 align='right'>
					<strong>Discount</strong>
			</td>
			<td align='right'>
				
			
			@if (!$billPosted)
				@if ($bill_discount)
					<strong><a href="{{ URL::to('bill_discounts/'.$bill_discount->discount_id) }}/edit">{{ $bill_discount->discount_amount }}%</a><strong>
				@else
					<strong><a href="{{ URL::to('bill_discounts/create/'.$bill->encounter_id) }}">None</a><strong>
				@endif
			@else
				@if ($bill_discount)
					<strong>{{ $bill_discount->discount_amount }}%<strong>
				@else
					<strong>None</strong>
				@endif
			@endif

			</td>
			@can('system-administrator')
			<td align='right'>
			</td>
			@endcan
	</tr>
	<tr>
			<td colspan=7 align='right'>
					<strong>Grand Total</strong>
			</td>
			<td align='right'>
					<strong>{{ number_format($bill_grand_total,2) }}<strong>
			</td>
			@can('system-administrator')
			<td align='right'>
			</td>
			@endcan
	</tr>
	<tr>
			<td colspan=7 align='right'>
					<strong>Total Payable include tax</strong>
			</td>
			<td align='right'>
					<strong>{{ number_format($total_payable,2) }}<strong>
			</td>
			@can('system-administrator')
			<td align='right'>
			</td>
			@endcan
	</tr>
</tbody>
</table>
</div>
@else
		<div class="widget style1 gray-bg">
		<h4>No items.</h4>
		</div>
@endif
<!-- Payments -->
<div class="widget style1 gray-bg">

<h4>Payments
@if (!$billPosted)
		<a href='/payments/create/{{ $patient->patient_id }}/{{ $encounter_id }}/{{ $non_claimable }}' class='btn btn-primary btn-sm pull-right'>New Payment</a>
@endif
</h4>
@if ($payments->total()>0)
<br>
<table class="table table-condensed">
	<tbody>
@foreach ($payments as $payment)
	<tr>
			<td>
					
					@if (!$billPosted)
					<a href='{{ URL::to('payments/'. $payment->payment_id . '/edit') }}'>
					@endif	
							{{$payment->payment_name}}
					@if (!$billPosted)
					</a>
					@endif
			</td>
			<td>
					{{ $payment->payment_description }}
			</td>
			<td align='right' width='100'>
					{{ number_format($payment->payment_amount,2) }}
			</td>
			@if (!$billPosted)
			<td align='right' width='80'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('payments/delete/'. $payment->payment_id) }}'>Delete</a>
			</td>
			@endif
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
					<strong>{{number_format($payment_total,2)}}</strong>
			</td>
			@if (!$billPosted)
			<td align='right'>
			</td>
			@endif
	</tr>
</tbody>
</table>
@else
	
		<h4 class='text-danger'>
			<strong>
				No payment collected.
			</strong>	
		</h4>
	<br>
	<br>
@endif
<table width='100%'>
	<tr>
			<td></td>
			<td>
				<div class='pull-right'>
					<strong>Deposits</strong>
				</div>
			</td>
			<td align='right' width='100'>
					<strong>{{number_format($deposit_total,2)}}</strong>
			</td>
			@if (!$billPosted)
			<td width='90'>
			</td>
			@endif
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
			<?php $bill_outstanding = $bill_grand_total-$payment_total-$deposit_total; ?>
					<strong>{{DojoUtility::roundUp($bill_outstanding)}}</strong>
			</td>
			@if (!$billPosted)
			<td width='90'>
			</td>
			@endif
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
					<strong>{{number_format($payment_total+$deposit_total-$bill_grand_total,2)}}</strong>
			</td>
			@if (!$billPosted)
			<td width='90'>
			</td>
			@endif
	</tr>
	@endif
</table>
</div>
<!--
@if ($pending>0) 
<div class="alert alert-warning" role="alert">
<strong>Warning !</strong> There are incomplete orders.
</div>
@endif
-->
@if (!$billPosted)
<div class="widget style1 gray-bg">
<?php
$claim_label = "";
if (!empty($encounter->sponsor_code)) {
	if ($non_claimable == 0) {
			$claim_label = "Claimable";
	} else {
			$claim_label = "Non-claimable";
	}
}
?>
<h4>Post {{ $claim_label }} Bill</h4>
{{ Form::open(['id'=>'post_form','url'=>'bills', 'class'=>'form-horizontal']) }} 
	<table>
		<tr>
				<td width='30'>
					<input type='checkbox' id='post_checkbox' value='1' onchange='javascript:enablePostButton()'>
				</td>
				<td>
					I have confirmed that all the information above are correct.
				</td>
		</tr>
	</table>
            {{ Form::hidden('encounter_id', $encounter_id, ['id'=>'encounter_id']) }}
            {{ Form::hidden('bill_grand_total', DojoUtility::roundUp($bill_grand_total)) }}
            {{ Form::hidden('bill_payment_total', $payment_total) }}
            {{ Form::hidden('bill_deposit_total', $deposit_total) }}
            {{ Form::hidden('bill_outstanding', DojoUtility::roundUp($bill_outstanding)) }}
            {{ Form::hidden('bill_change', $bill_change) }}
            {{ Form::hidden('bill_non_claimable', $non_claimable) }}
            {{ Form::hidden('bill_total', $bill_total) }}
			@if ($bill_discount)
            {{ Form::hidden('bill_discount', $bill_discount->discount_amount) }}
			@endif
            {{ Form::submit('Submit', ['class'=>'btn btn-primary btn-sm pull-right','id'=>'button_post']) }}
			<br>
			<br>
{{ Form::close() }}
</h4>
</div>
@else
<div class="widget style1 blue-bg">
<h4>
This bill has been posted.
</h4>
</div>
@endif
<script>
	function disablePostButton() {
			postForm = document.getElementById('post_form');
			postForm.button_post.disabled=true;
	}
	function enablePostButton() {
			postForm = document.getElementById('post_form');
			if (postForm.post_checkbox.checked==true) {
					postForm.button_post.disabled=false;
			} else {
					postForm.button_post.disabled=true;
			}
	}
	disablePostButton();
</script>
	

@endsection
