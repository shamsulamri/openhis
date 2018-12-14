@extends('layouts.app')

@section('content')
<h1>Inventory Batch Index
<a href='/inventory_batches/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/inventory_batch/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($inventory_batches->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>batch_number</th>
    <th>batch_id</th> 
	@can('system-administrator')
	<th></th>
	@endcan
	</tr>
  </thead>
	<tbody>
@foreach ($inventory_batches as $inventory_batch)
	<tr>
			<td>
					<a href='{{ URL::to('inventory_batches/'. $inventory_batch->batch_id . '/edit') }}'>
						{{$inventory_batch->batch_number}}
					</a>
			</td>
			<td>
					{{$inventory_batch->batch_id}}
			</td>
			@can('system-administrator')
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('inventory_batches/delete/'. $inventory_batch->batch_id) }}'>Delete</a>
			</td>
			@endcan
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $inventory_batches->appends(['search'=>$search])->render() }}
	@else
	{{ $inventory_batches->render() }}
@endif
<br>
@if ($inventory_batches->total()>0)
	{{ $inventory_batches->total() }} records found.
@else
	No record found.
@endif
@endsection
