@extends('layouts.app')

@section('content')
<h1>Ward List
<a href='/wards/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
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
