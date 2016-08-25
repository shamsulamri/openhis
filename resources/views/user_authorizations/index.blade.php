@extends('layouts.app')

@section('content')
<h1>User Authorization Index</h1>
<br>
<form action='/user_authorization/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/user_authorizations/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($user_authorizations->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Authorization</th>
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($user_authorizations as $user_authorization)
	<tr>
			<td>
					<a href='{{ URL::to('user_authorizations/'. $user_authorization->author_id . '/edit') }}'>
						{{$user_authorization->author_name}}
					</a>
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('user_authorizations/delete/'. $user_authorization->author_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $user_authorizations->appends(['search'=>$search])->render() }}
	@else
	{{ $user_authorizations->render() }}
@endif
<br>
@if ($user_authorizations->total()>0)
	{{ $user_authorizations->total() }} records found.
@else
	No record found.
@endif
@endsection
