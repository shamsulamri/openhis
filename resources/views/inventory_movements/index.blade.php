@extends('layouts.app')

@section('content')
<h1>Stock Movements
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
    <th>Document Number</th> 
    <th>Date</th> 
    <th>Movement</th>
    <th>Tag</th>
    <th>Store</th> 
    <th>Description</th> 
    <th>Status</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($inventory_movements as $inventory_movement)
	<tr>
			<td>
					{{ $inventory_movement->move_number }}
			</td>
			<td width='10%'>
					{{ DojoUtility::dateReadFormat($inventory_movement->created_at) }}
			</td>
			<td width='20%'>
					<a href='{{ URL::to('inventory_movements/'. $inventory_movement->move_id . '/edit') }}'>
						{{$inventory_movement->movement->move_name}}
					</a>
			</td>
			<td>
				{{ $inventory_movement->tag? $inventory_movement->tag->tag_name: '-' }}
			</td>
			<td width='20%'>
					{{$inventory_movement->store? $inventory_movement->store->store_name: '-'}}
			</td>
			<td>
					{{$inventory_movement->move_description }}
			</td>
			<td width='10'>
					{{ $inventory_movement->move_posted?'Close':'' }}
			</td>
			<td align='right'>
					<a class='btn btn-default btn-xs' href='{{ URL::to('inventory_movements/show/'.$inventory_movement->move_id.'?reason=stock') }}'>Line</a>
			@if ($inventory_movement->move_posted==0)
					<a class='btn btn-danger btn-xs' href='{{ URL::to('inventory_movements/delete/'. $inventory_movement->move_id) }}'>Delete</a>
			@endif
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
