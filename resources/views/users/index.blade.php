@extends('layouts.app')

@section('content')
<h1>User List</h1>
<br>
<form action='/user/search' method='post'>
	<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

<a href='/users/create' class='btn btn-primary'>Create</a>
<a href='/employees' class='btn btn-default pull-right'>Grab Employees</a>
<br>
<br>
@if ($users->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Group</th>
	<th>Username</th>
    <th>Employee ID</th> 
    <th></th> 
	</tr>
  </thead>
	<tbody>
@foreach ($users as $user)
	<tr>
			<td>
					<a href='{{ URL::to('users/'. $user->id . '/edit') }}'>
						{{$user->name}}
					</a>
			</td>
			<td>
					{{ $user->authorization->author_name }}
			</td>
			<td>
					{{$user->employee_id}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('users/delete/'. $user->id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $users->appends(['search'=>$search])->render() }}
	@else
	{{ $users->render() }}
@endif
<br>
@if ($users->total()>0)
	{{ $users->total() }} records found.
@else
	No record found.
@endif
@endsection
