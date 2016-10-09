@extends('layouts.app')

@section('content')
<h1>Department List</h1>
<br>
<form action='/department/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/departments/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($departments->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($departments as $department)
	<tr>
			<td>
					<a href='{{ URL::to('departments/'. $department->department_code . '/edit') }}'>
						{{$department->department_name}}
					</a>
			</td>
			<td>
					{{$department->department_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('departments/delete/'. $department->department_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $departments->appends(['search'=>$search])->render() }}
	@else
	{{ $departments->render() }}
@endif
<br>
@if ($departments->total()>0)
	{{ $departments->total() }} records found.
@else
	No record found.
@endif
@endsection
