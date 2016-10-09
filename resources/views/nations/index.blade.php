@extends('layouts.app')

@section('content')
<h1>Nation List</h1>
<br>
<form action='/nation/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/nations/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($nations->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($nations as $nation)
	<tr>
			<td>
					<a href='{{ URL::to('nations/'. $nation->nation_code . '/edit') }}'>
						{{$nation->nation_name}}
					</a>
			</td>
			<td>
					{{$nation->nation_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('nations/delete/'. $nation->nation_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $nations->appends(['search'=>$search])->render() }}
	@else
	{{ $nations->render() }}
@endif
<br>
@if ($nations->total()>0)
	{{ $nations->total() }} records found.
@else
	No record found.
@endif
@endsection
