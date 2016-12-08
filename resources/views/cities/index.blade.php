@extends('layouts.app')

@section('content')
<h1>City List
<a href='/cities/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/city/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($cities->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($cities as $city)
	<tr>
			<td>
					<a href='{{ URL::to('cities/'. $city->city_code . '/edit') }}'>
						{{$city->city_name}}
					</a>
			</td>
			<td>
					{{$city->city_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('cities/delete/'. $city->city_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $cities->appends(['search'=>$search])->render() }}
	@else
	{{ $cities->render() }}
@endif
<br>
@if ($cities->total()>0)
	{{ $cities->total() }} records found.
@else
	No record found.
@endif
@endsection
