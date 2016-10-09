@extends('layouts.app')

@section('content')
<h1>Tourist List</h1>
<br>
<form action='/tourist/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/tourists/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($tourists->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>tourist_name</th>
    <th>tourist_code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($tourists as $tourist)
	<tr>
			<td>
					<a href='{{ URL::to('tourists/'. $tourist->tourist_code . '/edit') }}'>
						{{$tourist->tourist_name}}
					</a>
			</td>
			<td>
					{{$tourist->tourist_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('tourists/delete/'. $tourist->tourist_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $tourists->appends(['search'=>$search])->render() }}
	@else
	{{ $tourists->render() }}
@endif
<br>
@if ($tourists->total()>0)
	{{ $tourists->total() }} records found.
@else
	No record found.
@endif
@endsection
