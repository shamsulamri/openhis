@extends('layouts.app')

@section('content')
<h1>Religion List</h1>
<br>
<form action='/religion/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/religions/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($religions->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($religions as $religion)
	<tr>
			<td>
					<a href='{{ URL::to('religions/'. $religion->religion_code . '/edit') }}'>
						{{$religion->religion_name}}
					</a>
			</td>
			<td>
					{{$religion->religion_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('religions/delete/'. $religion->religion_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $religions->appends(['search'=>$search])->render() }}
	@else
	{{ $religions->render() }}
@endif
<br>
@if ($religions->total()>0)
	{{ $religions->total() }} records found.
@else
	No record found.
@endif
@endsection
