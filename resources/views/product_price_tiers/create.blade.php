@extends('layouts.app')

@section('content')
<h1>
New Price Tier
</h1>
@include('common.errors')
<br>
{{ Form::model($product_price_tier, ['url'=>'product_price_tiers', 'class'=>'form-horizontal']) }} 
    
	@include('product_price_tiers.product_price_tier')
{{ Form::close() }}

@endsection
