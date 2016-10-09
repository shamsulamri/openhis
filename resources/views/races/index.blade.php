@extends('layouts.app')

@section('content')
<h1>Race List</h1>
<br>
<form action='/race/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/races/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($races->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($races as $race)
	<tr>
			<td>
					<a href='{{ URL::to('races/'. $race->race_code . '/edit') }}'>
						{{$race->race_name}}
					</a>
			</td>
			<td>
					{{$race->race_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('races/delete/'. $race->race_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $races->appends(['search'=>$search])->render() }}
	@else
	{{ $races->render() }}
@endif
<br>
@if ($races->total()>0)
	{{ $races->total() }} records found.
@else
	No record found.
@endif
@endsection
