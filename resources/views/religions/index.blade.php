@extends('layouts.app')

@section('content')
<h1>Religion List
<a href='/religions/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/religion/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($religions->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($religions as $religion)
	<tr>
			<td>
					<a href='{{ URL::to('religions/'. $religion->religion_code . '/edit') }}'>
						{{$religion->religion_name}}
					</a>
			</td>
			<td>
					{{$religion->religion_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('religions/delete/'. $religion->religion_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $religions->appends(['search'=>$search])->render() }}
	@else
	{{ $religions->render() }}
@endif
<br>
@if ($religions->total()>0)
	{{ $religions->total() }} records found.
@else
	No record found.
@endif
@endsection
