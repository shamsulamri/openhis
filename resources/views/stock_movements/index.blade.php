@extends('layouts.app')

@section('content')
<h1>Stock Movement List</h1>
<br>
<form action='/stock_movement/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/stock_movements/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($stock_movements->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($stock_movements as $stock_movement)
	<tr>
			<td>
					<a href='{{ URL::to('stock_movements/'. $stock_movement->move_code . '/edit') }}'>
						{{$stock_movement->move_name}}
					</a>
			</td>
			<td>
					{{$stock_movement->move_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('stock_movements/delete/'. $stock_movement->move_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $stock_movements->appends(['search'=>$search])->render() }}
	@else
	{{ $stock_movements->render() }}
@endif
<br>
@if ($stock_movements->total()>0)
	{{ $stock_movements->total() }} records found.
@else
	No record found.
@endif
@endsection
