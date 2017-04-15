@extends('layouts.app')

@section('content')
<h1>Team Index</h1>
<br>
<form action='/team/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<br>
<a href='/teams/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($teams->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>team_name</th>
    <th>team_code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($teams as $team)
	<tr>
			<td>
					<a href='{{ URL::to('teams/'. $team->team_code . '/edit') }}'>
						{{$team->team_name}}
					</a>
			</td>
			<td>
					{{$team->team_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('teams/delete/'. $team->team_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $teams->appends(['search'=>$search])->render() }}
	@else
	{{ $teams->render() }}
@endif
<br>
@if ($teams->total()>0)
	{{ $teams->total() }} records found.
@else
	No record found.
@endif
@endsection
