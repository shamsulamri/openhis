@extends('layouts.app')

@section('content')
<h1>Room List
<a href='/rooms/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/room/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($rooms->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($rooms as $room)
	<tr>
			<td>
					<a href='{{ URL::to('rooms/'. $room->room_code . '/edit') }}'>
						{{$room->room_name}}
					</a>
			</td>
			<td>
					{{$room->room_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('rooms/delete/'. $room->room_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $rooms->appends(['search'=>$search])->render() }}
	@else
	{{ $rooms->render() }}
@endif
<br>
@if ($rooms->total()>0)
	{{ $rooms->total() }} records found.
@else
	No record found.
@endif
@endsection
