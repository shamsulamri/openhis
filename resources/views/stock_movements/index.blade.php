@extends('layouts.app')

@section('content')
<h1>Stock Movement List
<a href='/stock_movements/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/stock_movements/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
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
