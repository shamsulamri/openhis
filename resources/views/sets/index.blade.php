@extends('layouts.app')

@section('content')
<h1>Order Set List<a href='/sets/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></a></h1>
<form action='/set/search' method='post'>
	<div class='input-group'>
	<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if ($sets->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
    <th>Owner</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($sets as $set)
	<tr>
			<td>
					<a href='{{ URL::to('sets/'. $set->set_code . '/edit') }}'>
						{{$set->set_name}}
					</a>
			</td>
			<td>
					{{$set->set_code}}
			</td>
			<td>
					{{ $set->owner['name'] }}
			</td>
			<td align='right'>
					<a class='btn btn-default btn-xs' href='{{ URL::to('sets/'. $set->set_code) }}'>Assets</a>
					@can('system-administrator')
					<a class='btn btn-danger btn-xs' href='{{ URL::to('sets/delete/'. $set->set_code) }}'>Delete</a>
					@endcan
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $sets->appends(['search'=>$search])->render() }}
	@else
	{{ $sets->render() }}
@endif
<br>
@if ($sets->total()>0)
	{{ $sets->total() }} records found.
@else
	No record found.
@endif
@endsection
