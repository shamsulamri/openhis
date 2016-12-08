@extends('layouts.app')

@section('content')
<h1>Occupation List
<a href='/occupations/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/occupation/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($occupations->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($occupations as $occupation)
	<tr>
			<td>
					<a href='{{ URL::to('occupations/'. $occupation->occupation_code . '/edit') }}'>
						{{$occupation->occupation_name}}
					</a>
			</td>
			<td>
					{{$occupation->occupation_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('occupations/delete/'. $occupation->occupation_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $occupations->appends(['search'=>$search])->render() }}
	@else
	{{ $occupations->render() }}
@endif
<br>
@if ($occupations->total()>0)
	{{ $occupations->total() }} records found.
@else
	No record found.
@endif
@endsection
