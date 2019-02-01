@extends('layouts.app')

@section('content')
@include('products.id')
<h1>Build Assembly</h1>
<br>
<form class='form-inline' action='/build_assembly/refresh/{{ $product->product_code }}' method='post' id='form_search'>
	<label>Source Store</label>
    {{ Form::select('store_code', $store,$store_code, ['class'=>'form-control','onchange'=>'refreshStore()']) }}
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-danger">{{ Session::get('message') }}</div>
@endif
<table class='table table-hover'>
 <thead>
<tr>
	<th>Components</th>
	<th>Unit</th>
	<th width='15%'><div align='right'>Quantity Per Item</div></th>
	<th width='15%'><div align='right'>Quantity On Hand</div></th>
	<th width='15%'><div align='right'>Quantity Required</div></th>
</tr>
 </thead>
<?php 
$max=99999; 
$can_build = True;
?>
@foreach ($boms as $bom)
<?php
$on_hand = $stock_helper->getStockOnHand($bom->product->product_code, $store_code);
if (empty($on_hand)) $can_build=False;
if ($on_hand>0 && $on_hand>=$bom->bom_quantity) {
	if ($on_hand/$bom->bom_quantity<$max) $max = $on_hand/$bom->bom_quantity;
} else {
		$max = 0;
}	
?>
<tr>
	<td>	
	{{ $bom->product->product_name }}
	</td>
	<td>
	{{ empty($bom->unitMeasure->unit_shortname) ? 'Unit' : $bom->unitMeasure->unit_shotname }}
	</td>
	<td align='right'>	
	{{ floatval($bom->bom_quantity) }}
	</td>
	<td align='right'>	
	{{ floatval($on_hand) }}
	</td>
	<td align='right'>	
	@if ($on_hand<($bom->bom_quantity*$quantity))
			{{ floatval($on_hand-$bom->bom_quantity*$quantity) }}
	@else
			-
	@endif
	</td>
</tr>
@endforeach
</table>
@if ($max>0 && $can_build)
<h4>Product on hand: <strong>{{ $stock_helper->getStockOnHand($product->product_code, $store_code) }}</strong></h4>
<h4>The maximum number of build: <strong>{{ floor($max) }}</strong></h4>
<br>
<form class='form-inline' action='/build_assembly/{{ $bom->product_code }}' method='post'>
	<label>Quanity to build</label>
	{{ Form::text('quantity', $quantity, ['class'=>'form-control','placeholder'=>'']) }}
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
	{{ Form::submit('Build', ['class'=>'btn btn-default']) }}
	{{ Form::hidden('max', floor($max)) }}
	{{ Form::hidden('store_code', $store_code) }}
</form>
@else
<h4>Build not possible</h4>
@endif

<script>
	function refreshStore() {
			document.forms['form_search'].submit();
	}
</script>
@endsection
