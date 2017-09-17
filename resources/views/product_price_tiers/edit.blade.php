@extends('layouts.app')

@section('content')
<h1>
Edit Product Price Tier
</h1>
@include('common.errors')
<br>
{{ Form::model($product_price_tier, ['route'=>['product_price_tiers.update',$product_price_tier->tier_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('product_price_tiers.product_price_tier')
{{ Form::close() }}

@endsection
