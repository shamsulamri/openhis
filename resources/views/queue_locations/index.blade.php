@extends('layouts.app')

@section('content')
<h1>Queue Location List
<a href='/queue_locations/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/queue_location/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if ($queue_locations->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
    <th>Department</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($queue_locations as $queue_location)
	<tr>
			<td>
					<a href='{{ URL::to('queue_locations/'. $queue_location->location_code . '/edit') }}'>
						{{$queue_location->location_name}}
					</a>
			</td>
			<td>
					{{$queue_location->location_code}}
			</td>
			<td>
					@if ($queue_location->department)
					{{$queue_location->department->department_name}}
					@endif
			</td>
			<td align='right'>
					<a class='btn btn-warning btn-xs' href='{{ URL::to('queue_locations/set/'. $queue_location->location_code) }}'>Set Location</a>
					@can('system-administrator')
					<a class='btn btn-danger btn-xs' href='{{ URL::to('queue_locations/delete/'. $queue_location->location_code) }}'>Delete</a>
					@endcan
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $queue_locations->appends(['search'=>$search])->render() }}
	@else
	{{ $queue_locations->render() }}
@endif
<br>
@if ($queue_locations->total()>0)
	{{ $queue_locations->total() }} records found.
@else
	No record found.
@endif
@endsection
