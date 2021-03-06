@extends('layouts.app')

@section('content')
<h1>Ward List
<div class='pull-right'>
<a href='/wards/forget' class='btn btn-primary'><span class='fa fa-stop-circle'></span></a>
<a href='/wards/create' class='btn btn-primary'><span class='glyphicon glyphicon-plus'></span></a>
</div>
</h1>
<form action='/ward/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($wards->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
    <th>Department</th>
	<!--
    <th>Gender</th>
    <th>Level</th>
	-->
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($wards as $ward)
	<tr>
			<td>
					<a href='{{ URL::to('wards/'. $ward->ward_code . '/edit') }}'>
						{{$ward->ward_name}}
					</a>
			</td>
			<td>
					{{$ward->ward_code}}
			</td>
			<td>
					@if ($ward->department)
					{{$ward->department->department_name}}
					@endif
			</td>
			<!--
			<td>
					@if ($ward->gender)
					{{$ward->gender->gender_name}}
					@endif
			</td>
			<td>
					{{$ward->ward_level}}
			</td>
			-->
			<td align='right'>
					<a class='btn btn-warning btn-xs' href='{{ URL::to('wards/set/'. $ward->ward_code) }}'>Set Ward</a>
					@can('system-administrator')
					<a class='btn btn-danger btn-xs' href='{{ URL::to('wards/delete/'. $ward->ward_code) }}'>Delete</a>
					@endcan
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $wards->appends(['search'=>$search])->render() }}
	@else
	{{ $wards->render() }}
@endif
<br>
@if ($wards->total()>0)
	{{ $wards->total() }} records found.
@else
	No record found.
@endif
@endsection
