@extends('layouts.app')

@section('content')
@include('products.id')
<h1>Stock Movements</h1>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<div class='row'>
	<div class='col-md-3'>
		<div class='list-group'>
			<a class='list-group-item' href="/products/{{ $product->product_code }}/option">Back</a>
		</div>
		<div class='list-group'>
		@foreach ($stores as $s)
		<a class='list-group-item @if ($s->store_code==$store_code) {!! 'active' !!} @endif' href="/stocks/{{ $product->product_code }}/{{ $s->store_code }}">{{ $s->store_name }}<span class='badge'>{{ $stockHelper->getStockCount($product->product_code, $s->store_code) }}</span></a>
		@endforeach
		</div>
	</div>
	<div class='col-md-9'>

		@if (!empty($store_code))
		<a href='/stocks/create/{{ $product->product_code }}/{{ $store_code }}' class='btn btn-primary'>Create</a>
		<br>
		<br>
		@endif
		@if ($stocks->total()>0)
		<table class="table table-hover">
		 <thead>
			<tr> 
			<th>Date</th> 
			<th>Movement</th>
			<th>Quantity</th>
			<th></th>
			</tr>
		  </thead>
			<tbody>
		@foreach ($stocks as $stock)
			<tr>
					<td>
							{{ date('d F Y', strtotime($stock->stock_date)) }}
					</td>
					<td>
							<a href='{{ URL::to('stocks/'. $stock->stock_id . '/edit') }}'>
								{{$stock->move_name}}
							</a>
					</td>
					<td>
							{{ str_replace('.00','',number_format($stock->stock_quantity,2)) }}
					</td>
					<td align='right'>
							<a class='btn btn-default btn-xs' href='{{ URL::to('stocks/'. $stock->stock_id . '/edit') }}'>Edit</a>
							<a class='btn btn-danger btn-xs' href='{{ URL::to('stocks/delete/'. $stock->stock_id) }}'>Delete</a>
					</td>
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
