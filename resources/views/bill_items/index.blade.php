@extends('layouts.app')

@section('content')
@include('patients.id_only')
<h1>
@if ($encounter->discharge)
Final Bill
@else
Interim Bill
@endif
</h1>
@if ($encounter->discharge)
	@if (!$encounter->bill)
		@if ($incomplete_orders>0 && $encounter->discharge->discharge_id)
		<div class='alert alert-danger' role='alert'>Patient has {{ $incomplete_orders }} incomplete order(s)</div>
		@else
		<br>
		@endif
	@endif
@endif
@if ($bills->total()>0)

<a class="btn btn-default" href="/discharges" role="button">Cancel</a>
@if (!$billPosted)
<a href='/bill_items/reload/{{ $encounter_id }}' class='btn btn-warning pull-right'>Reload Bill</a>
<p class='pull-right'>&nbsp;</p>
@endif
<a class="btn btn-default pull-right" href="{{ Config::get('host.report_server') }}/ReportServlet?report=bill&id={{ $encounter->encounter_id }}" role="button" target="_blank">Print Tax Invoice</a> 
<a class="btn btn-default pull-right" href="{{ Config::get('host.report_server') }}/ReportServlet?report=bill_simple&id={{ $encounter->encounter_id }}" role="button" target="_blank">Print Simple Invoice</a> 
<p class='pull-right'>&nbsp;</p>
<a class="btn btn-default pull-right" href="{{ Config::get('host.report_server') }}/ReportServlet?report=medical_certificate&id={{ $encounter->encounter_id }}" role="button" target="_blank">Print Medical Certificate</a>
<br>
<br>
<table class="table table-condensed">
 <thead>
	<tr> 
    <th>Item</th> 
    <th><div align='right'>Tax</div></th> 
    <th><div align='right'>Rate</div></th> 
    <th><div align='right'>#</div></th> 
    <th><div align='right'>Unit Price</div></th> 
    <th><div align='right'>Discount</div></th> 
    <th><div align='right'>Total</div></th> 
	@can('system-administrator')	
	<th></th>
	@endcan
	</tr>
</thead>
	<tbody>
		@foreach ($bills as $bill)
			<tr>
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
							{{ $bill->tax_code }}
					</td>
					<td align='right' width='50'>
							<?php if ($bill->tax_rate>0) { ?>
							{{ floatval($bill->tax_rate) }}
							%
							<?php } ?>
					</td>
					<td align='right' width='50'>
							{{$bill->bill_quantity}}
					</td>
					<td align='right' width='100'>
							{{ number_format($bill->bill_unit_price,2) }}
					</td>
					<td align='right' width='100'>
						<?php if ($bill->bill_discount>0) { ?>
							{{ floatval($bill->bill_discount) }} %
						<?php } ?>
							<?php if ($bill->bill_exempted==1) { ?>
								Exempted
							<?php } ?>
					</td>
					<td align='right' width='80'>
							{{ number_format($bill->bill_total,2) }}
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
			@can('system-administrator')
			<td align='right'>
			</td>
			@endcan
	</tr>
</tbody>
</table>
<!-- GST Summary -->
		<div class='row'>
			<div class='col-md-5'>
		<table class='table table-condensed'>
		 <thead>
			<tr> 
			<th>GST</th> 
			<th><div align='right'>Amount (RM)</div></th> 
			<th><div align='right'>GST (RM)</div></th> 
			</tr>
		</thead>
	@if (count($gst_total)>0)
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
	@endif
		</table>
			</div>
		</div>
@endif
@if (count($gst_total)==0)
	<h4>No GST detail</h4>
	<br>
@endif
<!-- Payments -->
<div class="widget style1 gray-bg">

@if ($encounter->sponsor)
	<h4>Sponsor</h4>
	<h4>
	<strong>
	{{ $encounter->sponsor->sponsor_name }}
	</strong>
	</h4>
	<hr>
@endif
<h4>Payments
@if (!$billPosted)
<a href='/payments/create/{{ $patient->patient_id }}/{{ $encounter_id }}' class='btn btn-primary btn-sm pull-right'>New Payment</a>
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
					<strong>{{number_format($bill_grand_total-$payment_total+$deposit_total,2)}}</strong>
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
<h4>Post Bill</h4>
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
            {{ Form::hidden('encounter_id', $encounter_id, ['id'=>'encounter_id','class'=>'form-control','placeholder'=>'',]) }}
            {{ Form::hidden('bill_grand_total', $bill_grand_total, ['class'=>'form-control','placeholder'=>'',]) }}
            {{ Form::hidden('bill_payment_total', $payment_total, ['class'=>'form-control','placeholder'=>'',]) }}
            {{ Form::hidden('bill_deposit_total', $deposit_total, ['class'=>'form-control','placeholder'=>'',]) }}
            {{ Form::hidden('bill_outstanding', $bill_outstanding, ['class'=>'form-control','placeholder'=>'',]) }}
            {{ Form::hidden('bill_change', $bill_change, ['class'=>'form-control','placeholder'=>'',]) }}
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
