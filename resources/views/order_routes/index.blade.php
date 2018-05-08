@extends('layouts.app')

@section('content')
<h1>Order Route Index
<a href='/order_routes/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/order_route/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($order_routes->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Redirect</th>
    <th>From</th> 
    <th>To</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($order_routes as $order_route)
	<tr>
			<td>
					<a href='{{ URL::to('order_routes/'. $order_route->route_id . '/edit') }}'>
						{{ $order_route->category->category_name }}
					</a>
			</td>
			<td>
						{{$order_route->encounter->encounter_name}}
			</td>
			<td>
						{{ $order_route->location->location_name }}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('order_routes/delete/'. $order_route->route_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $order_routes->appends(['search'=>$search])->render() }}
	@else
	{{ $order_routes->render() }}
@endif
<br>
@if ($order_routes->total()>0)
	{{ $order_routes->total() }} records found.
@else
	No record found.
@endif
@endsection
