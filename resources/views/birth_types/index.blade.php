@extends('layouts.app')

@section('content')
<h1>Birth Type List</h1>
<br>
<form action='/birth_type/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/birth_types/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($birth_types->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($birth_types as $birth_type)
	<tr>
			<td>
					<a href='{{ URL::to('birth_types/'. $birth_type->birth_code . '/edit') }}'>
						{{$birth_type->birth_name}}
					</a>
			</td>
			<td>
					{{$birth_type->birth_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('birth_types/delete/'. $birth_type->birth_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $birth_types->appends(['search'=>$search])->render() }}
	@else
	{{ $birth_types->render() }}
@endif
<br>
@if ($birth_types->total()>0)
	{{ $birth_types->total() }} records found.
@else
	No record found.
@endif
@endsection
