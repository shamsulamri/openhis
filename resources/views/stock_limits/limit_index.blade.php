@extends('layouts.app')

@section('content')
@include('products.id')
<h1>Stock Limits</h1>
<br>
    <div class='form-group'>
        <div class='col-sm-2 col-sm-offset-4'>
			<label>Min</label>
        </div>
        <div class='col-sm-2'>
			<label>Max</label>
        </div>
        <div class='col-sm-2'>
			<label>Reorder</label>
        </div>
    </div>
{{ Form::open(['url'=>['stock_limit', $product->product_code], 'class'=>'form-horizontal']) }} 
@foreach($stores as $store)
	<?php
	$min=NULL;
	$max=NULL;
	$reorder=NULL;
	if (!empty($limits[$store->store_code])) {
		$min = $limits[$store->store_code]->limit_min;
		$max = $limits[$store->store_code]->limit_max;
		$reorder = $limits[$store->store_code]->reorder_quantity;
	}
	?>
    <div class='form-group  @if ($errors->has('store_code')) has-error @endif'>
		<label for='store_code' class='col-sm-4 control-label'>
			@if ($store->store)
			{{ $store->store->store_name }}
			@else
			{{ $store->store_name }}
			@endif
		</label>
        <div class='col-sm-2'>
			{{ Form::text($store->store_code."_min", $min, ['class'=>'form-control']) }}
        </div>
        <div class='col-sm-2'>
			{{ Form::text($store->store_code."_max", $max, ['class'=>'form-control']) }}
        </div>
        <div class='col-sm-2'>
			{{ Form::text($store->store_code."_reorder", $reorder, ['class'=>'form-control']) }}
        </div>
    </div>
@endforeach
    <div class='form-group'>
        <div class="col-sm-offset-4 col-sm-8">
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
	{{ Form::hidden('stores', $ids) }}
{{ Form::close() }}
</table>
@endsection
