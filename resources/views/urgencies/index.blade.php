@extends('layouts.app')

@section('content')
<h1>Urgency List
<a href='/urgencies/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/urgency/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($urgencies->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($urgencies as $urgency)
	<tr>
			<td>
					<a href='{{ URL::to('urgencies/'. $urgency->urgency_code . '/edit') }}'>
						{{$urgency->urgency_name}}
					</a>
			</td>
			<td>
					{{$urgency->urgency_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('urgencies/delete/'. $urgency->urgency_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $urgencies->appends(['search'=>$search])->render() }}
	@else
	{{ $urgencies->render() }}
@endif
<br>
@if ($urgencies->total()>0)
	{{ $urgencies->total() }} records found.
@else
	No record found.
@endif
@endsection
