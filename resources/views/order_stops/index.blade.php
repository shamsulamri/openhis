@extends('layouts.app')

@section('content')
<h1>Order Stop Index
<a href='/order_stops/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/order_stop/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($order_stops->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>order_id</th>
    <th>stop_id</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($order_stops as $order_stop)
	<tr>
			<td>
					<a href='{{ URL::to('order_stops/'. $order_stop->stop_id . '/edit') }}'>
						{{$order_stop->order_id}}
					</a>
			</td>
			<td>
					{{$order_stop->stop_id}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('order_stops/delete/'. $order_stop->stop_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $order_stops->appends(['search'=>$search])->render() }}
	@else
	{{ $order_stops->render() }}
@endif
<br>
@if ($order_stops->total()>0)
	{{ $order_stops->total() }} records found.
@else
	No record found.
@endif
@endsection
