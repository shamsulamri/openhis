@extends('layouts.app')

@section('content')
@include('products.id')
<h1>Reorder Limits</h1>
<br>
{{ Form::open(['url'=>['stock_limit', $product->product_code], 'class'=>'form-horizontal']) }} 
@foreach($stores as $store)
	<?php
	$value = null;
	if (!empty($limits[$store->store_code])) {
		$value =  $limits[$store->store_code];
	}
	?>
    <div class='form-group  @if ($errors->has('store_code')) has-error @endif'>
        <label for='store_code' class='col-sm-3 control-label'>{{ $store->store->store_name }}</label>
        <div class='col-sm-3'>
			{{ Form::text($store->store_code, $value, ['class'=>'form-control']) }}
        </div>
    </div>
@endforeach
    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
	{{ Form::hidden('stores', $ids) }}
{{ Form::close() }}
@endsection
