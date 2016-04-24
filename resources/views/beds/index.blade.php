@extends('layouts.app')

@section('content')
<h1>Bed List</h1>
<br>
<form action='/bed/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<br>
<a href='/beds/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($beds->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($beds as $bed)
	<tr>
			<td>
					<a href='{{ URL::to('beds/'. $bed->bed_code . '/edit') }}'>
						{{$bed->bed_name}}
					</a>
			</td>
			<td>
					{{$bed->bed_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('beds/delete/'. $bed->bed_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $beds->appends(['search'=>$search])->render() }}
	@else
	{{ $beds->render() }}
@endif
<br>
@if ($beds->total()>0)
	{{ $beds->total() }} records found.
@else
	No record found.
@endif
@endsection
