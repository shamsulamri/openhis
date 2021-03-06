@extends('layouts.app')

@section('content')
<h1>Department List
<a href='/departments/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/department/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
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
