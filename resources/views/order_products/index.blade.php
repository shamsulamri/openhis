@extends('layouts.app2')

@section('content')
<style>
.pagination {
    font-size: 60%;
}
</style>
<form class='form-inline' action='/order_product/search' method='post' id='form_search'>
	<div class="row">

			<div class="col-xs-12">
			<div class='input-group'>
					<input id='search' onkeypress='clearOrderSet()' type='text' class='form-control' placeholder="Enter product name or code" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
				<span class='input-group-btn'>
					<button class="btn btn-default" type="submit" value="Submit"><span class='glyphicon glyphicon-search'></span></button>
				</span>
			</div>
			</div>
	</div>
	<br>
	{{ Form::select('categories', $categories, $category_code, ['id'=>'categories','class'=>'form-control']) }}
	@can('module-consultation')
	<br>	
	<div class="row">
			<div class="col-xs-12">
			<h4>Order Set</h4>
            {{ Form::select('set_code', $sets,$set_value, ['class'=>'form-control','maxlength'=>'10','onchange'=>'orderSet()','id'=>'orderset']) }}
			</div>
	</div>
	@endcan
<!--
<br>
	<a href='/order_product/drug' class='btn btn-default btn-xs'>Drug History</a>
	<input type='hidden' name='set_value' value="{{ $set_value }}">
-->
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
	<input_type='hidden' name="encounter_id" value="{{ $consultation->encounter_id }}">
</form>
<br>
@if (!is_null($order_products))
		@if ($order_products->total()>0)
		<form action='/orders/multiple' method='post'>
				<table class="table table-condensed">
					<tbody>
						@foreach ($order_products as $order_product)
							<tr>
									<td width='10'>
								<?php
									$total_on_hand = $order_product->getTotalOnHand()?:0;
									$show_checkbox = False;
									$button_status="btn-primary";
									if ($order_product->product_stocked==1) {
											if ($total_on_hand>0 & $negetive_stock==0) {
													$show_checkbox = True;
											}
									}
									if ($order_product->product_stocked==0) {
										$show_checkbox = True;
									}

									if ($negetive_stock == 1) {
										$show_checkbox = True;
									}
								?>
									@if ($show_checkbox)
										{{ Form::checkbox($order_product->product_code, 1, null,['class'=>'i-checks']) }}
									@endif
									</td>
									<td>
										{{ ucfirst(strtoupper($order_product->product_name)) }}
										<br>
										<small>
										{{ $order_product->product_code }}
										@if (!empty($order_product->category))
										- {{ $order_product->category->category_name }}
										@endif
										@if ($order_product->uomDefaultPrice($consultation->encounter))
										(RM{{ number_format($order_product->uomDefaultPrice($consultation->encounter)->uom_price,2) }})
										@endif
										</small>
										@if ($tab=='drug')
											<!--
											<p class='pull-right'>
												{{ $dojo->diffForHumans($order_product->created_at) }}
												{{ (DojoUtility::dateLongFormat($order_product->created_at)) }}
											</p>
											-->
											<br>
											@if ($order_product->drug_dosage>0)
											{{ $order_product->drug_dosage }} {{ $order_product->dosage_name }}
											@endif

											@if ($order_product->route_name != '')
											, {{ $order_product->route_name }}
											@endif
											@if ($order_product->frequency_name !='')
											, {{ $order_product->frequency_name }}
											@endif
											@if ($order_product->drug_duration>0)
											{{ $order_product->drug_duration }} {{ $order_product->period_name }}
											@endif
										@endif
									</td>
									<td width='10'>
<?php
	$button_status="btn-primary";
	/*
	if ($order_product->product_stocked==1) {
			if ($total_on_hand==0 & $negetive_stock==0) $button_status = "btn-default disabled";
	}
	 */
?>
										<a href='/orders/single/{{ $order_product->product_code }}?_search={{ $search }}&_page={{ $page }}&_set_value={{ $set_value }}&category_code={{ $category_code }}' class='btn {{ $button_status }}'>
											<span class='glyphicon glyphicon-plus'></span>
										</a>
									</td>
							</tr>
						@endforeach
				@endif
				</tbody>
				</table>
			<input type='hidden' name="_token" value="{{ csrf_token() }}">
			<input type='hidden' name='_set_value' value="{{ $set_value }}">
			<input type='hidden' name='_page' value="{{ $page }}">
			<input type='hidden' name='_search' value="{{ $search }}">
			@if ($order_products->total()>0)
			{{ Form::submit('Add Selection', ['class'=>'btn btn-primary btn-xs']) }}
			@endif
		</form>
		@if ($order_products->total()>10)
		<br>
		{{ $order_products->appends(['search'=>$search, 'set_code'=>$set_value, 'categories'=>$category_code])->render() }}
		@endif
		<br>
		@if ($order_products->total()>0)
			{{ $order_products->total() }} records found.
		@else
			No record found.
		@endif
@endif
<script>
	var frame = parent.document.getElementById('frameDetail');
	/*
	frame.src = "/orders";
	 */
	frame.src = "/orders";

	function orderSet() {
			document.getElementById('categories').selectedIndex=0;
			document.getElementById('search').value="";
			document.forms['form_search'].submit();
	}

	function clearOrderSet() {
			document.getElementById('orderset').selectedIndex=0;
	}

	$("#search").on("paste", function() {
			$(this).val($(this).val().concat(";"));
	});
</script>
@endsection
