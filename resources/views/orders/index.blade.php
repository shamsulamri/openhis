@extends('layouts.app')

@section('content')
<h1>Order Index</h1>
<br>
<form action='/order/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<br>
<a href='/orders/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($orders->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($orders as $order)
	<tr>
			<td>
					<a href='{{ URL::to('orders/'. $order->order_id . '/edit') }}'>
						{{$order->product_code}}
					</a>
			</td>
			<td>
					{{$order->order_id}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('orders/delete/'. $order->order_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $orders->appends(['search'=>$search])->render() }}
	@else
	{{ $orders->render() }}
@endif
<br>
@if ($orders->total()>0)
	{{ $orders->total() }} records found.
@else
	No record found.
@endif
@endsection
