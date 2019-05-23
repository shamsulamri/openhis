@extends('layouts.app')

@section('content')
<h1>User List
<a href='/users/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/user/search' method='post' class='form-horizontal'>

	<div class="row">
			<div class="col-xs-5">
					<div class='form-group'>
						<label class='col-sm-1 control-label'><div align='left'>Find</div></label>
						<div class='col-sm-11'>
							<input type='text' class='form-control' placeholder="Enter name or employee identification" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
						</div>
					</div>
			</div>
			<div class="col-xs-7">
					<div class='form-group'>
						<label class='col-sm-4 control-label'>Authorization Group</label>
						<div class='col-sm-8'>
							{{ Form::select('author_id', $groups, $author_id, ['class'=>'form-control','maxlength'=>'10']) }}
						</div>
					</div>
			</div>
	</div>
	<button class="btn btn-primary" type="submit" value="Submit">Search</button>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<!--
<a href='/employees' class='btn btn-default pull-right'>Grab Employees</a>
-->
<br>
@if ($users->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Group</th>
	<th>Username</th>
    <th>Employee ID</th> 
    <th></th> 
	</tr>
  </thead>
	<tbody>
@foreach ($users as $user)
	<tr>
			<td>
					<a href='{{ URL::to('users/'. $user->id . '/edit') }}'>
						{{$user->name}}
					</a>
			</td>
			<td>
					{{ $user->authorization->author_name }}
			</td>
			<td>
					{{$user->username}}
			</td>
			<td>
					{{$user->employee_id}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('users/delete/'. $user->id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search) | isset($author_id)) 
	{{ $users->appends(['search'=>$search, 'author_id'=>$author_id])->render() }}
	@else
	{{ $users->render() }}
@endif
<br>
@if ($users->total()>0)
	{{ $users->total() }} records found.
@else
	No record found.
@endif
@endsection
