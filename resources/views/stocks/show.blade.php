@extends('layouts.app')

@section('content')
@include('products.id')
<h1>Stock Movements</h1>
@cannot('module-inventory')
<h2>{{ $store->store_name }}</h2>
@endcannot
<br>
<div class='row'>
	<div class='col-md-4'>
		<div class='list-group'>
		@foreach ($stores as $s)
		<a class='list-group-item @if ($s->store_code==$store_code) {!! 'active' !!} @endif' href="/stocks/{{ $product->product_code }}/{{ $s->store_code }}">{{ $s->store->store_name }}<span class='badge'>{{ floatval($s->stock_quantity) }}</span></a>
		@endforeach
		</div>
	</div>
	<div class='col-md-8'>

		@if (!empty($store_code))
		<!--
		<a href='/stocks/create/{{ $product->product_code }}/{{ $store_code }}' class='btn btn-primary'>Create</a>
		<br>
		<br>
		-->
		@endif
		@if ($stocks->total()>0)
		<table class="table table-hover">
		 <thead>
			<tr> 
			<th>Date</th> 
			<th>Movement</th>
			<th><div align='right'>Quantity</div></th>
			<th><div align='right'>Value</div></th>
			@can('system-administrator')
			<th></th>
			@endcan
			</tr>
		  </thead>
			<tbody>
		@foreach ($stocks as $stock)
			<tr>
					<td>
							{{ date('d F Y H:i', strtotime($stock->stock_datetime)) }}
							@if ($stock->user)
							<br>
							<small>by {{ $stock->user->name }}</small>
							@endif
					</td>
					<td>
								{{$stock->move_name}}
							@if (!empty($stock->stock_description))
								<br>
								<small>{{ $stock->stock_description }}</small>
							@endif
					</td>
					<td align='right'>
							{{ floatval($stock->getStockQuantity()) }}
					</td>
					<td align='right'>
							{{ number_format(abs($stock->stock_value),2) }}
					</td>
					@can('system-administrator')
					<td align='right'>
							<a class='btn btn-default btn-xs' href='{{ URL::to('stocks/'. $stock->stock_id . '/edit') }}'>Edit</a>
							<a class='btn btn-danger btn-xs' href='{{ URL::to('stocks/delete/'. $stock->stock_id) }}'>Delete</a>
					</td>
					@endcan
			</tr>
		@endforeach
		@endif
		</tbody>
		</table>
		{{ $stocks->appends(['store_code'=>$store_code,'product_code'=>$product->product_code])->render() }}
		<br>
		@if ($stocks->total()>0)
			{{ $stocks->total() }} records found.
		@else
			No record found.
		@endif
	</div>
</div>
@endsection
