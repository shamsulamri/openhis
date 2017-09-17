@extends('layouts.app')

@section('content')
<h1>
Delete Product Charge Tier
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $product_price_tier->charge_code }}
{{ Form::open(['url'=>'product_price_tiers/'.$product_price_tier->tier_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/product_price_tiers" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
