@extends('layouts.app')

@section('content')
<h1>Birth Complication List</h1>
<br>
<form action='/birth_complication/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/birth_complications/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($birth_complications->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($birth_complications as $birth_complication)
	<tr>
			<td>
					<a href='{{ URL::to('birth_complications/'. $birth_complication->complication_code . '/edit') }}'>
						{{$birth_complication->complication_name}}
					</a>
			</td>
			<td>
					{{$birth_complication->complication_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('birth_complications/delete/'. $birth_complication->complication_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $birth_complications->appends(['search'=>$search])->render() }}
	@else
	{{ $birth_complications->render() }}
@endif
<br>
@if ($birth_complications->total()>0)
	{{ $birth_complications->total() }} records found.
@else
	No record found.
@endif
@endsection
