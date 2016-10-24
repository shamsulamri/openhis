@extends('layouts.app')

@section('content')
@include('products.id')
<h1>Stock Movements</h1>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<div class='row'>
	<div class='col-md-4'>
		<div class='list-group'>
		@foreach ($stores as $s)
		<a class='list-group-item @if ($s->store_code==$store_code) {!! 'active' !!} @endif' href="/stocks/{{ $product->product_code }}/{{ $s->store_code }}">{{ $s->store_name }}<span class='badge'>{{ $stockHelper->getStockCount($product->product_code, $s->store_code) }}</span></a>
		@endforeach
		</div>
	</div>
	<div class='col-md-8'>

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
			<th><div align='right'>Quantity</div></th>
			@can('system-administrator')
			<th></th>
			@endcan
			</tr>
		  </thead>
			<tbody>
		@foreach ($stocks as $stock)
			<tr>
					<td>
							{{ date('d F Y H:i', strtotime($stock->getStockDate())) }}
							@if ($stock->user)
							<br>
							<small>by {{ $stock->user->name }}</small>
							@endif
					</td>
					<td>
							<a href='{{ URL::to('stocks/'. $stock->stock_id . '/edit') }}'>
								{{$stock->move_name}}
							</a>
							@if (!empty($stock->stock_description))
								<br>
								<small>{{ $stock->stock_description }}</small>
							@endif
					</td>
					<td align='right'>
							{{ str_replace('.00','',number_format($stock->stock_quantity,2)) }}
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
