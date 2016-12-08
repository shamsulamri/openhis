@extends('layouts.app')

@section('content')
<h1>Relationship List
<a href='/relationships/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/relationship/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

<a href='/relationships/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($relationships->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($relationships as $relationship)
	<tr>
			<td>
					<a href='{{ URL::to('relationships/'. $relationship->relation_code . '/edit') }}'>
						{{$relationship->relation_name}}
					</a>
			</td>
			<td>
					{{$relationship->relation_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('relationships/delete/'. $relationship->relation_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $relationships->appends(['search'=>$search])->render() }}
	@else
	{{ $relationships->render() }}
@endif
<br>
@if ($relationships->total()>0)
	{{ $relationships->total() }} records found.
@else
	No record found.
@endif
@endsection
