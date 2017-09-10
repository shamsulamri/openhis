@extends('layouts.app')

@section('content')
<h1>Priority Index
<a href='/priorities/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/priority/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($priorities->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>priority_name</th>
    <th>priority_code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($priorities as $priority)
	<tr>
			<td>
					<a href='{{ URL::to('priorities/'. $priority->priority_code . '/edit') }}'>
						{{$priority->priority_name}}
					</a>
			</td>
			<td>
					{{$priority->priority_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('priorities/delete/'. $priority->priority_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $priorities->appends(['search'=>$search])->render() }}
	@else
	{{ $priorities->render() }}
@endif
<br>
@if ($priorities->total()>0)
	{{ $priorities->total() }} records found.
@else
	No record found.
@endif
@endsection
