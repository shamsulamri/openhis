@extends('layouts.app')

@section('content')
<h1>City Index</h1>
<br>
<form action='/city/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/cities/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($cities->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>city_name</th>
    <th>city_code</th> 
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
