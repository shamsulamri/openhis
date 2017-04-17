@extends('layouts.app')

@section('content')
<h1>Team Member List</h1>
<!--
<a href='{{ url('teams') }}' class='btn btn-primary'>Back</a>
<br>
<br>
-->
<div class="row">
  <div class="col-md-6">
		<h2>User List</h2>
		<form action='/team/search_member/{{ $team->team_code }}' method='post'>
			<div class='input-group'>
				<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
				<span class='input-group-btn'>
					<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
				</span>
			</div>
			<input type='hidden' name="_token" value="{{ csrf_token() }}">
			<input type='hidden' name="team_code" value="{{ $team->team_code }}">
		</form>
		<br>
		@if ($users->total()>0)
		<table class="table table-hover">
		 <thead>
			<tr> 
			<th>Name</th>
			<th></th>
			</tr>
		  </thead>
			<tbody>
		@foreach ($users as $user)
			<tr>
					<td>
							<a href='{{ URL::to('team_members/'. $user->id . '/edit') }}'>
								{{$user->name}}
							</a>
					</td>
					<td align='right'>
							<a class='btn btn-primary btn-xs' href='{{ URL::to('team/add/'. $user->username.'/'.$team->team_code) }}'>
								<span class='glyphicon glyphicon-plus'></span>
							</a>
					</td>
			</tr>
		@endforeach
		@endif
		</tbody>
		</table>
		@if (isset($search)) 
			{{ $users->appends(['search'=>$search, 'tean_code'=>$team->team_code])->render() }}
			@else
			{{ $users->appends(['team_code'=>$team->team_code])->render() }}
		@endif
		<br>
		@if ($users->total()>0)
			{{ $users->total() }} records found.
		@else
			No record found.
		@endif
 </div>
  <div class="col-md-6">
		<h2>{{ $team->team_name }}</h2>
		<table class="table table-hover">
		 <thead>
			<tr> 
			<th>Name</th>
			<th></th>
			</tr>
		  </thead>
			<tbody>
		@foreach ($team_members as $team_member)
			<tr>
					<td>
						{{$team_member->user->name}}
					</td>
					<td align='right'>
							<a class='btn btn-danger btn-xs' href='{{ URL::to('team_members/delete/'. $team_member->member_id) }}'>
								<span class='glyphicon glyphicon-minus'></span>
							</a>
					</td>
			</tr>
		@endforeach
		</tbody>
		</table>
 </div>
@endsection
