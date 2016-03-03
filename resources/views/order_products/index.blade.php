@extends('layouts.app')

@section('content')
<h1>Order Product Index</h1>
<br>
<form action='/order_product/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
	<input type='hidden' name='consultation_id' value="{{ $consultation_id }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<br>
<a href='/order_products/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($order_products->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>product_name</th>
    <th>product_code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($order_products as $order_product)
	<tr>
			<td>
					<a href='{{ URL::to('order_products/'. $order_product->product_code . '/edit') }}'>
						{{$order_product->product_name}}
					</a>
			</td>
			<td>
					{{$order_product->product_code}}
			</td>
			<td align='right'>
					<a class='btn btn-primary btn-xs' href='{{ URL::to('orders/create?product_code='. $order_product->product_code . '&consultation_id='. $consultation_id) }}'>Select</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $order_products->appends(['search'=>$search, 'consultation_id'=>$consultation_id])->render() }}
	@else
	{{ $order_products->appends(['consultation_id'=>$consultation_id])->render() }}
@endif
<br>
@if ($order_products->total()>0)
	{{ $order_products->total() }} records found.
@else
	No record found.
@endif
@endsection
