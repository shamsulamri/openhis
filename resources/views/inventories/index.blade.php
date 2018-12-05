@extends('layouts.app')

@section('content')
<h1>Inventory Index
<a href='/inventories/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/inventory/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($inventories->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>move_code</th>
    <th>inv_id</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($inventories as $inventory)
	<tr>
			<td>
					<a href='{{ URL::to('inventories/'. $inventory->inv_id . '/edit') }}'>
						{{$inventory->move_code}}
					</a>
			</td>
			<td>
					{{$inventory->inv_id}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('inventories/delete/'. $inventory->inv_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $inventories->appends(['search'=>$search])->render() }}
	@else
	{{ $inventories->render() }}
@endif
<br>
@if ($inventories->total()>0)
	{{ $inventories->total() }} records found.
@else
	No record found.
@endif
@endsection
