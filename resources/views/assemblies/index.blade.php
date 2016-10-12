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
	<th width='200'><div align='right'>Quantity Per Item</div></th>
	<th width='200'><div align='right'>Quantity On Hand</div></th>
	<th width='200'><div align='right'>Quantity Needed</div></th>
</tr>
<?php $max=99999; ?>
@foreach ($boms as $bom)
<?php

	if ($bom->product->product_on_hand/$bom->bom_quantity<$max) $max = $bom->product->product_on_hand/$bom->bom_quantity;
	
?>
<tr>
	<td>	
	{{ $bom->product->product_name }}
	</td>
	<td align='right'>	
	{{ str_replace('.00','',$bom->bom_quantity) }}
	<!--
	{{ empty($bom->unitMeasure->unit_shortname) ? 'Unit' : $bom->unitMeasure->unit_shotname }}
	-->
	</td>
	<td align='right'>	
	{{ $bom->product->product_on_hand }}
	</td>
	<td align='right'>	
	@if ($bom->product->product_on_hand<($bom->bom_quantity*$quantity))
			{{ $bom->product->product_on_hand-$bom->bom_quantity*$quantity }}
	@else
			0
	@endif
	</td>
</tr>
@endforeach
</table>
<h4>The maximum number of build is <strong>{{ round($max) }}</strong></h4>
<br>
<form class='form-inline' action='/build_assembly/{{ $bom->product_code }}' method='post'>
	<label>Store</label>
    {{ Form::select('store_code', $store,$store_code, ['class'=>'form-control']) }}
	<label>Quanity to Build</label>
	{{ Form::text('quantity', $quantity, ['class'=>'form-control','placeholder'=>'']) }}
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
	{{ Form::submit('Build', ['class'=>'btn btn-default']) }}
	{{ Form::hidden('max', round($max)) }}
</form>
@endsection
