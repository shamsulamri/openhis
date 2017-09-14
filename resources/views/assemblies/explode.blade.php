@extends('layouts.app')

@section('content')
@include('products.id')
<h1>Explode Assembly</h1>
<br>
@if (Session::has('message'))
    <div class="alert alert-danger">{{ Session::get('message') }}</div>
@endif
<?php
$on_hand = $stock_helper->getStockCountByStore($product->product_code, $store_code);
?>
@if ($on_hand>0)
<h4>The maximum number of dismantle is <strong>{{ $on_hand }}</strong></h4>
<br>
<form class='form-inline' action='/explode_assembly/{{ $product->product_code }}' method='post'>
	<label>Receiving Store</label>
    {{ Form::select('store_code', $store,$store_code, ['class'=>'form-control']) }}
	<label>Quanity</label>
	{{ Form::text('quantity', null, ['class'=>'form-control','placeholder'=>'']) }}
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
	{{ Form::submit('Submit', ['class'=>'btn btn-default']) }}
</form>
@else
<h4>No product to dismantle</h4>
@endif
@endsection
