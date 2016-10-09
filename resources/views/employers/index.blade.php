@extends('layouts.app')

@section('content')
<h1>Employer List</h1>
<br>
<form action='/employer/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/employers/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($employers->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>employer_name</th>
    <th>employer_code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($employers as $employer)
	<tr>
			<td>
					<a href='{{ URL::to('employers/'. $employer->employer_code . '/edit') }}'>
						{{$employer->employer_name}}
					</a>
			</td>
			<td>
					{{$employer->employer_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('employers/delete/'. $employer->employer_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $employers->appends(['search'=>$search])->render() }}
	@else
	{{ $employers->render() }}
@endif
<br>
@if ($employers->total()>0)
	{{ $employers->total() }} records found.
@else
	No record found.
@endif
@endsection
