@extends('layouts.app')

@section('content')
<h1>State List</h1>
<br>
<form action='/state/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/states/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($states->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($states as $state)
	<tr>
			<td>
					<a href='{{ URL::to('states/'. $state->state_code . '/edit') }}'>
						{{$state->state_name}}
					</a>
			</td>
			<td>
					{{$state->state_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('states/delete/'. $state->state_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $states->appends(['search'=>$search])->render() }}
	@else
	{{ $states->render() }}
@endif
<br>
@if ($states->total()>0)
	{{ $states->total() }} records found.
@else
	No record found.
@endif
@endsection
