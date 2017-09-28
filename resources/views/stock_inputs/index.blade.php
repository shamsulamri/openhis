@extends('layouts.app')

@section('content')
<h1>Stock Movement
<a href='/stock_inputs/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/stock_input/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($stock_inputs->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Movement Type</th>
    <th>Description</th>
    <th>Date</th> 
	<th>User</th>
    <th>Status</th> 
	</tr>
  </thead>
	<tbody>
@foreach ($stock_inputs as $stock_input)
	<tr>
			<td>
					{{$stock_input->movement->move_name}}
			</td>
			<td>
					{{$stock_input->input_description }}
			</td>
			<td>
					{{ DojoUtility::dateTimeReadFormat($stock_input->created_at) }}
			</td>
			<td>
					{{$stock_input->user->name }}
			</td>
			<td>
					@if ($stock_input->input_close==1)
						<div class='label label-success'>
						Close
						</div>
					@else
						<div class='label label-warning'>
						Open
						</div>
					@endif
			</td>
			<td align='right'>
					@if ($stock_input->input_close==0)
					<a class='btn btn-default btn-xs' href='{{ URL::to('stock_inputs/show/'. $stock_input->input_id) }}'>Resume</a>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('stock_inputs/delete/'. $stock_input->input_id) }}'>Delete</a>
					@else
							<!--
					<a class='btn btn-default btn-xs' href='{{ URL::to('stock_inputs/show/'. $stock_input->input_id . '/edit') }}'>View</a>			
-->
					@endif
			@can('system-administrator')
					<a class='btn btn-default btn-xs' href='{{ URL::to('stock_inputs/'. $stock_input->input_id . '/edit') }}'>Edit</a>			
			@endcan
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $stock_inputs->appends(['search'=>$search])->render() }}
	@else
	{{ $stock_inputs->render() }}
@endif
<br>
@if ($stock_inputs->total()>0)
	{{ $stock_inputs->total() }} records found.
@else
	No record found.
@endif
@endsection
