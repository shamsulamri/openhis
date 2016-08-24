@extends('layouts.app')

@section('content')
<h1>Employee Index</h1>
<br>
<form action='/employee/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
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
