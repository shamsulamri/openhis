@extends('layouts.app')

@section('content')
<h1><a href='/products'>Product Index</a> / Stock Movements</h1>
<br>
@include('products.id')
<form action='/stock/search' method='post'>
    {{ Form::select('store_code', $store,$store_code, ['class'=>'form-control']) }}
	<br>{{ Form::submit('Refresh', ['class'=>'btn btn-default']) }}
	{{ Form::hidden('product_code', $product->product_code) }}
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
@if (!empty($store_code))
<br>
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
    <th>Store</th>
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
					{{ $stock->store_name }}
			</td>
			<td>
					{{ number_format($stock->stock_quantity,2) }}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('stocks/delete/'. $stock->stock_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $stocks->appends(['search'=>$search])->render() }}
	@else
	{{ $stocks->render() }}
@endif
<br>
@if ($stocks->total()>0)
	{{ $stocks->total() }} records found.
@else
	No record found.
@endif
@endsection
