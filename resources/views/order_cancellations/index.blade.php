@extends('layouts.app')

@section('content')
<h1>Order Cancellation List</h1>
<br>
<form action='/order_cancellation/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/order_cancellations/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($order_cancellations->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>cancel_reason</th>
    <th>cancel_id</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($order_cancellations as $order_cancellation)
	<tr>
			<td>
					<a href='{{ URL::to('order_cancellations/'. $order_cancellation->cancel_id . '/edit') }}'>
						{{$order_cancellation->cancel_reason}}
					</a>
			</td>
			<td>
					{{$order_cancellation->cancel_id}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('order_cancellations/delete/'. $order_cancellation->cancel_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $order_cancellations->appends(['search'=>$search])->render() }}
	@else
	{{ $order_cancellations->render() }}
@endif
<br>
@if ($order_cancellations->total()>0)
	{{ $order_cancellations->total() }} records found.
@else
	No record found.
@endif
@endsection
