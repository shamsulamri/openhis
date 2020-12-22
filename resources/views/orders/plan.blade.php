@extends('layouts.app')

@section('content')	
<style>
iframe { border: 1px #e5e5e5 solid; }
</style>
@if (!empty($consultation))
		@can('module-consultation')
				@include('consultations.panel')		
				<h1>Orders</h1>
		@endcan
@endif

@include('orders.tab')

@if ($consultation->encounter->bill || $consultation->encounter->lock_orders)
		@include('orders.order_stop')
@else
<div class="row">
<?php
	for ($i=0;$i<sizeof($products);$i++) {
?>
		<div class="col-xs-6">
	@foreach ($products[$i] as $product)
			<div class='form-group'>
				<div class='col-md-12'>
					<h4>
						{{ Form::checkbox($product->product_code, '1', in_array($product->product_code, $orders)?'true':null , ['id'=>$product->product_code, 'onchange'=>'addOrder("'.$product->product_code.'")']) }} 
				&nbsp;{{ ucwords(strtolower($product->product_name)) }}
						<div class='pull-right'><font color='#ababab'><small>{{ $product->product_code }}</small></font></div>
					</h4> 
				</div>
			</div>
	@endforeach
		</div>
<?php } ?>
</div>


<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
			function addOrder(product_code) {
					var dataString = "product_code="+product_code+"&consultation_id={{ $consultation->consultation_id }}";

					console.log(document.getElementById(product_code).checked);

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
</script>
@endif
@endsection
