@extends('layouts.app')

@section('content')
<h1>Title List</h1>
<br>
<form action='/title/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/titles/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($titles->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($titles as $title)
	<tr>
			<td>
					<a href='{{ URL::to('titles/'. $title->title_code . '/edit') }}'>
						{{$title->title_name}}
					</a>
			</td>
			<td>
					{{$title->title_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('titles/delete/'. $title->title_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $titles->appends(['search'=>$search])->render() }}
	@else
	{{ $titles->render() }}
@endif
<br>
@if ($titles->total()>0)
	{{ $titles->total() }} records found.
@else
	No record found.
@endif
@endsection
