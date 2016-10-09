@extends('layouts.app')

@section('content')
<h1>Urgency List</h1>
<br>
<form action='/urgency/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/urgencies/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($urgencies->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($urgencies as $urgency)
	<tr>
			<td>
					<a href='{{ URL::to('urgencies/'. $urgency->urgency_code . '/edit') }}'>
						{{$urgency->urgency_name}}
					</a>
			</td>
			<td>
					{{$urgency->urgency_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('urgencies/delete/'. $urgency->urgency_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $urgencies->appends(['search'=>$search])->render() }}
	@else
	{{ $urgencies->render() }}
@endif
<br>
@if ($urgencies->total()>0)
	{{ $urgencies->total() }} records found.
@else
	No record found.
@endif
@endsection
