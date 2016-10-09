@extends('layouts.app')

@section('content')
<h1>Drug Route List</h1>
<br>
<form action='/drug_route/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/drug_routes/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($drug_routes->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($drug_routes as $drug_route)
	<tr>
			<td>
					<a href='{{ URL::to('drug_routes/'. $drug_route->route_code . '/edit') }}'>
						{{$drug_route->route_name}}
					</a>
			</td>
			<td>
					{{$drug_route->route_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('drug_routes/delete/'. $drug_route->route_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $drug_routes->appends(['search'=>$search])->render() }}
	@else
	{{ $drug_routes->render() }}
@endif
<br>
@if ($drug_routes->total()>0)
	{{ $drug_routes->total() }} records found.
@else
	No record found.
@endif
@endsection
