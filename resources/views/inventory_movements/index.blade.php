@extends('layouts.app')

@section('content')
<h1>Inventory Movement Index
<a href='/inventory_movements/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/inventory_movement/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($inventory_movements->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>move_code</th>
    <th>move_id</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($inventory_movements as $inventory_movement)
	<tr>
			<td>
					<a href='{{ URL::to('inventory_movements/'. $inventory_movement->move_id . '/edit') }}'>
						{{$inventory_movement->move_code}}
					</a>
			</td>
			<td>
					{{$inventory_movement->move_id}}
			</td>
			<td align='right'>
					<a class='btn btn-default btn-xs' href='{{ URL::to('inventory_movements/show/'.$inventory_movement->move_id) }}'>Line</a>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('inventory_movements/delete/'. $inventory_movement->move_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $inventory_movements->appends(['search'=>$search])->render() }}
	@else
	{{ $inventory_movements->render() }}
@endif
<br>
@if ($inventory_movements->total()>0)
	{{ $inventory_movements->total() }} records found.
@else
	No record found.
@endif
@endsection
