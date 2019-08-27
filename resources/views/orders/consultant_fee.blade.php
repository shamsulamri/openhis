@extends('layouts.app')

@section('content')	
<style>
iframe { border: 1px #e5e5e5 solid; }
</style>
@if (!empty($consultation))
@can('module-consultation')
		@include('consultations.panel')		
		<h1>Plan</h1>
@endcan
@endif

<ul class="nav nav-tabs">
  <li @if ($plan=='laboratory') class="active" @endif><a href="plan?plan=laboratory">Laboratory</a></li>
  <li @if ($plan=='imaging') class="active" @endif><a href="plan?plan=imaging">Imaging</a></li>
  <li><a href="procedure">Procedures</a></li>
  <li><a href="medication">Medications</a></li>
  <li @if ($plan=='fee_consultant') class="active" @endif><a href="plan?plan=fee_consultant">Fees</a></li>
  <li><a href="discussion">Discussion</a></li>
  <li><a href="make">Orders</a></li>
</ul>
<br>

<div class="row">
<?php
	for ($i=0;$i<sizeof($products);$i++) {
?>
		<div class="col-xs-6">
	@foreach ($products[$i] as $product)
<?php
		$amount = $product->uomDefaultPrice()->uom_price;
		$order = $order_helper->getOrderByConsultation($product->product_code);
		if (!empty($order)) {
			$amount = $order->order_unit_price;
		}
?>
			<div class='form-group'>
				<div class='col-md-7'>
					<h4>
						{{ Form::checkbox($product->product_code, '1', in_array($product->product_code, $orders)?'true':null , ['id'=>$product->product_code, 'onchange'=>'addOrder("'.$product->product_code.'")']) }} 
				&nbsp;{{ ucwords(strtolower($product->product_name)) }}
					</h4> 
				</div>
				<div class='col-md-3'>
            			{{ Form::text($product->product_code.'_amount', $amount, ['id'=>$product->product_code.'_amount','class'=>'form-control','placeholder'=>'','maxlength'=>'10']) }}
						{{ Form::hidden($product->product_code.'_price', $product->uomDefaultPrice()->uom_price, ['id'=>$product->product_code.'_price']) }}
				</div>
				<div class='col-md-1'>
            			<a class="btn btn-success" href="javascript:updateAmount('{{ $product->product_code }}');" role="button"><i class="glyphicon glyphicon-time"></i></a>
				</div>
			</div>
	@endforeach
		</div>
<?php } ?>
</div>


<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
$(document).ready(function(){


			$(document).on('focusout', 'input', function(e) {
					if (e.currentTarget.type == 'text') {
							id = e.currentTarget.id;
							amount = document.getElementById(id).value;
							product_code = id.split('_')[0];
							updateOrder(product_code, amount);
					}
			});

});

			function updateAmount(product_code) {
					price = parseInt(document.getElementById(product_code+"_price").value);
					amount = 0;

					if (isNaN(price)==false) {
						amount = price*1.5;
						document.getElementById(product_code+"_amount").value = amount;
						updateOrder(product_code, amount);
					} else {
						document.getElementById(product_code+"_amount").value = "";
					}


			}

			function addOrder(product_code) {

					price = parseInt(document.getElementById(product_code+"_price").value);
					amount = document.getElementById(product_code+"_amount").value;
					if (isNaN(amount)) {
						amount = 0;
					}

					var dataString = "product_code="+product_code+"&consultation_id={{ $consultation->consultation_id }}&amount="+amount;

					if (document.getElementById(product_code).checked) {
							$.ajax({
								type: "POST",
								headers: {'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')},
								url: "{{ route('orders.add') }}",
								data: dataString,
								success: function(data){
									$('#orderList').html(data);
								}
							});

					} else {
							document.getElementById(product_code+"_amount").value = price;
							$.ajax({
								type: "POST",
								headers: {'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')},
								url: "{{ route('orders.remove') }}",
								data: dataString,
								success: function(data){
									$('#orderList').html(data);
								}
							});
					}

			}

			function updateOrder(product_code, amount) {
					console.log("Update order");
					var dataString = "product_code="+product_code+"&consultation_id={{ $consultation->consultation_id }}&amount="+amount;

					if (document.getElementById(product_code).checked) {
							$.ajax({
								type: "POST",
								headers: {'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')},
								url: "{{ route('orders.update_order') }}",
								data: dataString,
								success: function(data){
									$('#orderList').html(data);
								}
							});

					} 
			}
</script>
@endsection
