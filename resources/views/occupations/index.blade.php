@extends('layouts.app')

@section('content')
<h1>Occupation List</h1>
<br>
<form action='/occupation/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/occupations/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($occupations->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($occupations as $occupation)
	<tr>
			<td>
					<a href='{{ URL::to('occupations/'. $occupation->occupation_code . '/edit') }}'>
						{{$occupation->occupation_name}}
					</a>
			</td>
			<td>
					{{$occupation->occupation_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('occupations/delete/'. $occupation->occupation_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $occupations->appends(['search'=>$search])->render() }}
	@else
	{{ $occupations->render() }}
@endif
<br>
@if ($occupations->total()>0)
	{{ $occupations->total() }} records found.
@else
	No record found.
@endif
@endsection
