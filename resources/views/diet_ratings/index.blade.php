@extends('layouts.app')

@section('content')
<h1>Diet Rating List
<a href='/diet_ratings/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/diet_rating/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($diet_ratings->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($diet_ratings as $diet_rating)
	<tr>
			<td>
					<a href='{{ URL::to('diet_ratings/'. $diet_rating->rate_code . '/edit') }}'>
						{{$diet_rating->rate_name}}
					</a>
			</td>
			<td>
					{{$diet_rating->rate_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('diet_ratings/delete/'. $diet_rating->rate_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $diet_ratings->appends(['search'=>$search])->render() }}
	@else
	{{ $diet_ratings->render() }}
@endif
<br>
@if ($diet_ratings->total()>0)
	{{ $diet_ratings->total() }} records found.
@else
	No record found.
@endif
@endsection
