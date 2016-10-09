@extends('layouts.app')

@section('content')
<h1>Sponsor Index</h1>
<br>
<form action='/sponsor/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/sponsors/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($sponsors->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($sponsors as $sponsor)
	<tr>
			<td>
					<a href='{{ URL::to('sponsors/'. $sponsor->sponsor_code . '/edit') }}'>
						{{$sponsor->sponsor_name}}
					</a>
			</td>
			<td>
					{{$sponsor->sponsor_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('sponsors/delete/'. $sponsor->sponsor_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $sponsors->appends(['search'=>$search])->render() }}
	@else
	{{ $sponsors->render() }}
@endif
<br>
@if ($sponsors->total()>0)
	{{ $sponsors->total() }} records found.
@else
	No record found.
@endif
@endsection
