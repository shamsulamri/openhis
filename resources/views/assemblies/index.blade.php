@extends('layouts.app')

@section('content')
@include('products.id')
<h1>Build Assembly</h1>
<a class="btn btn-default" href="/products/{{ $product->product_code }}/option" role="button">Back</a>
<br>
<br>
@if (Session::has('message'))
    <div class="alert alert-danger">{{ Session::get('message') }}</div>
@endif
<table class='table table-hover'>
<tr>
	<th>Components</th>
	<th width='200'><div align='right'>Per Item Quantity</div></th>
	<th width='200'><div align='right'>Quantity On Hand</div></th>
	<th width='200'><div align='right'>Quantity Needed</div></th>
</tr>
<?php $max=99999; ?>
@foreach ($products as $product)
<?php

	if ($product->product_on_hand/$product->bom_quantity<$max) $max = $product->product_on_hand/$product->bom_quantity;
	
?>
<tr>
	<td>	
	{{ $product->product_name }}
	</td>
	<td align='right'>	
	{{ str_replace('.00','',$product->bom_quantity) }}
	<!--
	{{ empty($product->unitMeasure->unit_shortname) ? 'Unit' : $product->unitMeasure->unit_shotname }}
	-->
	</td>
	<td align='right'>	
	{{ $product->product_on_hand }}
	</td>
	<td align='right'>	
	@if ($product->product_on_hand<($product->bom_quantity*$quantity))
	{{ $product->product_on_hand-$product->bom_quantity }}
	@else
	0
	@endif
	</td>
</tr>
@endforeach
</table>
<h4>The maximum number of build is <strong>{{ round($max) }}</strong></h4>
<br>
<form class='form-inline' action='/build_assembly/{{ $product->product_code }}' method='post'>
	<label>Store</label>
    {{ Form::select('store_code', $store,$store_code, ['class'=>'form-control']) }}
	<label>Quanity to Build</label>
	{{ Form::text('quantity', $quantity, ['class'=>'form-control','placeholder'=>'']) }}
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
	{{ Form::submit('Build', ['class'=>'btn btn-default']) }}
</form>
@endsection
