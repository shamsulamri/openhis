@extends('layouts.app')

@section('content')
<h1>Nation List
<a href='/nations/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/nation/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($nations->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($nations as $nation)
	<tr>
			<td>
					<a href='{{ URL::to('nations/'. $nation->nation_code . '/edit') }}'>
						{{$nation->nation_name}}
					</a>
			</td>
			<td>
					{{$nation->nation_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('nations/delete/'. $nation->nation_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $nations->appends(['search'=>$search])->render() }}
	@else
	{{ $nations->render() }}
@endif
<br>
@if ($nations->total()>0)
	{{ $nations->total() }} records found.
@else
	No record found.
@endif
@endsection
