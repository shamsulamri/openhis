@extends('layouts.app')

@section('content')
<h1>Bed Movement List</h1>
<br>
<form action='/bed_movement/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/bed_movements/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($bed_movements->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>admission_id</th>
    <th>move_id</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($bed_movements as $bed_movement)
	<tr>
			<td>
					<a href='{{ URL::to('bed_movements/'. $bed_movement->move_id . '/edit') }}'>
						{{$bed_movement->admission_id}}
					</a>
			</td>
			<td>
					{{$bed_movement->move_id}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('bed_movements/delete/'. $bed_movement->move_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $bed_movements->appends(['search'=>$search])->render() }}
	@else
	{{ $bed_movements->render() }}
@endif
<br>
@if ($bed_movements->total()>0)
	{{ $bed_movements->total() }} records found.
@else
	No record found.
@endif
@endsection
