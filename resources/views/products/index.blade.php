@extends('layouts.app')

@section('content')
<h1>Product Index</h1>
<br>
<form action='/product/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Enter product name or code" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<br>
<a href='/products/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($products->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($products as $product)
	<tr>
			<td>
					<a href='{{ URL::to('products/'. $product->product_code . '/edit') }}'>
						{{$product->product_name}}
					</a>
			</td>
			<td>
					{{$product->product_code}}
			</td>
			<td align='right'>
					<a class='btn btn-default btn-xs' href='{{ URL::to('stocks/'. $product->product_code) }}'>Stock</a>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('products/delete/'. $product->product_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $products->appends(['search'=>$search])->render() }}
	@else
	{{ $products->render() }}
@endif
<br>
@if ($products->total()>0)
	{{ $products->total() }} records found.
@else
	No record found.
@endif
@endsection
