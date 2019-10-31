@extends('layouts.app')

@section('content')	
<style>
iframe { border: 1px #e5e5e5 solid; }
</style>
@if (!empty($consultation))
@can('module-consultation')
		@include('consultations.panel')		
<h1 class="text-danger"><i class="fa fa-warning"></i>&nbsp;DO NOT USE THIS. STILL UNDER DEVELOPMENT</h1>
		<h1>Plan</h1>
@endcan
@endif

<ul class="nav nav-tabs">
  <li @if ($plan=='laboratory') class="active" @endif><a href="plan?plan=laboratory">Laboratory</a></li>
  <li @if ($plan=='imaging') class="active" @endif><a href="/imaging">Imaging</a></li>
  <li><a href="procedure">Procedures</a></li>
  <li><a href="medication">Medications</a></li>
  <li @if ($plan=='fee_consultant') class="active" @endif><a href="plan?plan=fee_consultant">Fees</a></li>
  <li><a href="discussion">Discussion</a></li>
  <li><a href="make">Orders</a></li>
</ul>
<br>

<table>
 <thead>
	<tr> 
    <th></th>
    <th><div align='center'>Normal</div></th> 
	<th><div align='center'>After Hours</div></th>
	<th></th>
	<th><div align='left'>Amount</div></th>
	</tr>
  </thead>
<?php
	for ($i=0;$i<sizeof($products);$i++) {
?>
	@foreach ($products[$i] as $product)
<?php
			$product_root = substr($product->product_code,0,strlen($product->product_code)-1);
			$amount = null;
			if (array_key_exists($product_root, $amounts)) {
				$amount = $amounts[$product_root];
			}
?>
	<tr>
			<td height='10'>
			</tr>
	</tr>
	<tr>
			<td width='25%'>
					<h4>{{ ucwords(strtolower($product->product_name)) }}</h4>
			</td>
			<td width='10%' align='center'>
					{{ Form::radio($product_root,'A', (in_array($product_root.'A', $ordered_items))?true:false, ['id'=>$product_root.'A'])  }} 
			</td>
			<td width='10%' align='center'>
					{{ Form::radio($product_root,'B', (in_array($product_root.'B', $ordered_items))?true:false, ['id'=>$product_root.'B'])  }}
			</td>
			<td width='4%'>
			</td>
			<td width='10%' align='right'>
					{{ Form::text($product_root.'_amount', $amount, ['id'=>$product_root.'_amount','class'=>'form-control']) }}
			</td>
			<td width='45%'>
					{{ Form::hidden($product_root.'_price', $product->uomDefaultPrice()->uom_price, ['id'=>$product_root.'_price']) }}
			</td>
	</tr>
	@endforeach
<?php 
	}
?>
</table>


<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
$(document).ready(function(){

		$(':radio').mousedown(function(){
				var $self = $(this);
				if( $self.is(':checked') ){
						var uncheck = function(){
								setTimeout(function(){$self.removeAttr('checked');},0);
						};
						var unbind = function(){
								$self.unbind('mouseup',up);
						};
						var up = function(){
								uncheck();
								unbind();
						};
						$self.bind('mouseup',up);
						$self.one('mouseout', unbind);
						console.log("Uncheck...");
						productCode = this.name + this.value;
						document.getElementById(this.name + "_amount").value = "";
						console.log(productCode);
						removeOrder(productCode);
				}
		});

		$('input[type=radio]').change(function() {
				productCode = this.name + this.value;
				addOrder(productCode, this.name);
				document.getElementById(this.name + "_amount").value =	document.getElementById(this.name + "_price").value 
				if (this.value == 'A') {
					removeOrder(this.name + 'B')
				} else {
					removeOrder(this.name + 'A')
					updateAmount(this.name);
				}
		});

			$(document).on('focusout', 'input', function(e) {
					if (e.currentTarget.type == 'text') {
							id = e.currentTarget.id;
							amount = document.getElementById(id).value;
							product_root = id.split('_')[0];
							editAmount(product_root);
					}
			});

});

			function editAmount(product_root) {
					amount = parseInt(document.getElementById(product_root+"_amount").value);
					postfix = null;
					if (document.getElementById(product_root+'A').checked == true) {
							postfix = 'A';
					}

					if (document.getElementById(product_root+'B').checked == true) {
							postfix = 'B';
					}

					if (postfix != null) {
							updateOrder(product_root+postfix, amount);
					}
			}

			function updateAmount(product_root) {
					price = parseInt(document.getElementById(product_root+"_price").value);
					amount = 0;
					postfix = null;
					if (document.getElementById(product_root+'A').checked == true) {
							postfix = 'A';
					}

					if (document.getElementById(product_root+'B').checked == true) {
							postfix = 'B';
					}

					if (postfix != null) {
							if (isNaN(price)==false) {
								amount = price*1.5;
								document.getElementById(product_root+"_amount").value = amount;
								updateOrder(product_root+postfix, amount);
							} else {
								document.getElementById(product_root+"_amount").value = "";
							}
					}


			}

			function addOrder(product_code, product_root) {
					amount = document.getElementById(product_root+"_amount").value;
					if (isNaN(amount)) {
						amount = 0;
					}

					var dataString = "product_code="+product_code+"&consultation_id={{ $consultation->consultation_id }}&amount="+amount;

					$.ajax({
						type: "POST",
						headers: {'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')},
						url: "{{ route('orders.add') }}",
						data: dataString,
						success: function(data){
							$('#orderList').html(data);
						}
					});
			}

			function removeOrder(product_code) {

					amount=0;
					var dataString = "product_code="+product_code+"&consultation_id={{ $consultation->consultation_id }}&amount="+amount;

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

			function updateOrder(product_code, amount) {
					console.log("Update order");
					console.log(product_code);
					var dataString = "product_code="+product_code+"&consultation_id={{ $consultation->consultation_id }}&amount="+amount;
					console.log(dataString);

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
