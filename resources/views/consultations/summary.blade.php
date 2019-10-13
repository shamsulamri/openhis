
@extends('layouts.app')

@section('content')
@include('consultations.panel')
<h1>
	@if ($consultation->encounter->encounter_code == 'outpatient')
		Suspend	Consultation
	@else
		End Consultation
	@endif
</h1>
<br>

<!--
@if ($fees==0)
	<div class='alert alert-warning' role='alert'>
	<p>
	<strong>Alert !</strong> You have not entered you consultation fee.
	</p>
	</div>
@endif
-->

@if ($orders->count()==0) 
<h3>You have no orders.</h3>
<br>
@else
<h3>Following are your orders:</h3>

<?php $category_code=''; ?>
@foreach ($orders as $order)
	@if ($order->product->category_code != $category_code)
		<br>
		<h3>{{ $order->product->category->category_name }}</h3>
	@endif
		- {{ $order->product->product_name }}
	@if ($order->orderDrug)
		{{ $order_helper->getPrescription($order->order_id) }}
	@endif
		<br>
	<?php $category_code = $order->product->category_code; ?>
@endforeach

<br>
<h4>
@endif
<div class="widget style1 gray-bg">
<table>
		<tr>
				<!--
				<td width='20'>
					<input type='checkbox' id='post_checkbox' value='1'>
				</td>
				-->
				<td>
					I have confirmed that all the information above are correct.
<a class='btn btn-primary' id='post_button' href='/consultations/close'>Confirm</a>
				</td>
		</tr>
</table>
</h4>
</div>
<script>
/*
$(document).ready(function() {

	function disablePostButton() {
		$('#post_button').attr('disabled', true);
		$('#post_button').attr('href', '#');
	}

	$("#post_checkbox").change(function() {
			if (this.checked) {
				$('#post_button').attr('disabled', false);
				$('#post_button').attr('href', '/consultations/close');
			} else {
				$('#post_button').attr('disabled', true);
				$('#post_button').attr('href', '#');
			}
	});
	
	disablePostButton();
});
 */
</script>
@endsection
