@extends('layouts.app')

@section('content')
<h1>Team Member Index</h1>
<br>
<form action='/team_member/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<br>
<a href='/team_members/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($team_members->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>team_code</th>
    <th>member_id</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($team_members as $team_member)
	<tr>
			<td>
					<a href='{{ URL::to('team_members/'. $team_member->member_id . '/edit') }}'>
						{{$team_member->team_code}}
					</a>
			</td>
			<td>
					{{$team_member->member_id}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('team_members/delete/'. $team_member->member_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $team_members->appends(['search'=>$search])->render() }}
	@else
	{{ $team_members->render() }}
@endif
<br>
@if ($team_members->total()>0)
	{{ $team_members->total() }} records found.
@else
	No record found.
@endif
@endsection
