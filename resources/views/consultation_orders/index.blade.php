@extends('layouts.app')

@section('content')
<h1>Consultation Order Index</h1>
<br>
<form action='/consultation_order/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<br>
<a href='/order_products/{{ $consultation_id }}' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($consultation_orders->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>product_code</th>
    <th>order_id</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($consultation_orders as $consultation_order)
	<tr>
			<td>
					<a href='{{ URL::to('consultation_orders/'. $consultation_order->order_id . '/edit') }}'>
						{{$consultation_order->product_code}}
					</a>
			</td>
			<td>
					{{$consultation_order->order_id}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('consultation_orders/delete/'. $consultation_order->order_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $consultation_orders->appends(['search'=>$search])->render() }}
	@else
	{{ $consultation_orders->render() }}
@endif
<br>
@if ($consultation_orders->total()>0)
	{{ $consultation_orders->total() }} records found.
@else
	No record found.
@endif
@endsection
