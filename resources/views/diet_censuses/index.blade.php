@extends('layouts.app')

@section('content')
<h1>Diet Census Index
<a href='/diet_censuses/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/diet_census/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($diet_censuses->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>census_date</th>
    <th>census_id</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($diet_censuses as $diet_census)
	<tr>
			<td>
					<a href='{{ URL::to('diet_censuses/'. $diet_census->census_id . '/edit') }}'>
						{{$diet_census->census_date}}
					</a>
			</td>
			<td>
					{{$diet_census->census_id}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('diet_censuses/delete/'. $diet_census->census_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $diet_censuses->appends(['search'=>$search])->render() }}
	@else
	{{ $diet_censuses->render() }}
@endif
<br>
@if ($diet_censuses->total()>0)
	{{ $diet_censuses->total() }} records found.
@else
	No record found.
@endif
@endsection
