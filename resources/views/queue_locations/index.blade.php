@extends('layouts.app')

@section('content')
<h1>Queue Location Index</h1>
<br>
<form action='/queue_location/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<br>
<a href='/queue_locations/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($queue_locations->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
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
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('queue_locations/delete/'. $queue_location->location_code) }}'>Delete</a>
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
