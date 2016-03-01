@extends('layouts.app')

@section('content')
<h1>Purchase Order Index</h1>
<br>
<form action='/purchase_order/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<br>
<a href='/purchase_orders/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($purchase_orders->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($purchase_orders as $purchase_order)
	<tr>
			<td>
					<a href='{{ URL::to('purchase_orders/'. $purchase_order->purchase_id . '/edit') }}'>
						{{$purchase_order->purchase_date}}
					</a>
			</td>
			<td>
					{{$purchase_order->purchase_id}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('purchase_orders/delete/'. $purchase_order->purchase_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $purchase_orders->appends(['search'=>$search])->render() }}
	@else
	{{ $purchase_orders->render() }}
@endif
<br>
@if ($purchase_orders->total()>0)
	{{ $purchase_orders->total() }} records found.
@else
	No record found.
@endif
@endsection
