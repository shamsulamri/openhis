@extends('layouts.app')

@section('content')
<h1>Order Set Index</h1>
<br>
<form action='/order_set/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<br>
<a href='/order_sets/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($order_sets->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>product_code</th>
    <th>set_code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($order_sets as $order_set)
	<tr>
			<td>
					<a href='{{ URL::to('order_sets/'. $order_set->set_code . '/edit') }}'>
						{{$order_set->product_code}}
					</a>
			</td>
			<td>
					{{$order_set->set_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('order_sets/delete/'. $order_set->set_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $order_sets->appends(['search'=>$search])->render() }}
	@else
	{{ $order_sets->render() }}
@endif
<br>
@if ($order_sets->total()>0)
	{{ $order_sets->total() }} records found.
@else
	No record found.
@endif
@endsection
