@extends('layouts.app')

@section('content')
<h1>Gender List</h1>
<br>
<form action='/gender/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/genders/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($genders->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($genders as $gender)
	<tr>
			<td>
					<a href='{{ URL::to('genders/'. $gender->gender_code . '/edit') }}'>
						{{$gender->gender_name}}
					</a>
			</td>
			<td>
					{{$gender->gender_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('genders/delete/'. $gender->gender_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $genders->appends(['search'=>$search])->render() }}
	@else
	{{ $genders->render() }}
@endif
<br>
@if ($genders->total()>0)
	{{ $genders->total() }} records found.
@else
	No record found.
@endif
@endsection
