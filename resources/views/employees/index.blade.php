@extends('layouts.app')

@section('content')
<h1>Employee Index</h1>
</h1>
<form action='/employee/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

<!--
<a href='/employees/create' class='btn btn-primary'>Create</a>
<br>
<br>
-->
@if ($employees->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>IC/Passport</th>
    <th>Employee ID</th> 
    <th></th> 
	</tr>
  </thead>
	<tbody>
@foreach ($employees as $employee)
	<tr>
			<td>
					<a href='{{ URL::to('employees/'. $employee->empid . '/edit') }}'>
						{{$employee->name}}
					</a>
			</td>
			<td>
					{{$employee->ic_passport}}
			</td>
			<td>
					{{$employee->empid}}
			</td>
			<td align='right'>
					<a class='btn btn-default btn-xs' href='{{ URL::to('employees/create_user/'. $employee->empid) }}'>Create User</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $employees->appends(['search'=>$search])->render() }}
	@else
	{{ $employees->render() }}
@endif
<br>
@if ($employees->total()>0)
	{{ $employees->total() }} records found.
@else
	No record found.
@endif
@endsection
