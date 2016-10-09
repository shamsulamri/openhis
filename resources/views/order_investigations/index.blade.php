@extends('layouts.app')

@section('content')
<h1>Order Investigation List</h1>
<br>
<form action='/order_investigation/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/order_investigations/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($order_investigations->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>order_id</th>
    <th>id</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($order_investigations as $order_investigation)
	<tr>
			<td>
					<a href='{{ URL::to('order_investigations/'. $order_investigation->id . '/edit') }}'>
						{{$order_investigation->order_id}}
					</a>
			</td>
			<td>
					{{$order_investigation->id}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('order_investigations/delete/'. $order_investigation->id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $order_investigations->appends(['search'=>$search])->render() }}
	@else
	{{ $order_investigations->render() }}
@endif
<br>
@if ($order_investigations->total()>0)
	{{ $order_investigations->total() }} records found.
@else
	No record found.
@endif
@endsection
