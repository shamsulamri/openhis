@extends('layouts.app')

@section('content')
<h1>Team Index
<a href='/teams/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/team/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($teams->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Team</th>
    <th>Code</th> 
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
					<a class='btn btn-default btn-xs' href='{{ URL('teams/'.$team->team_code) }}'>Members</a>
					@can('system-administrator')
					<a class='btn btn-danger btn-xs' href='{{ URL::to('teams/delete/'. $team->team_code) }}'>Delete</a>
					@endcan
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
