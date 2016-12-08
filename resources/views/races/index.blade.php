@extends('layouts.app')

@section('content')
<h1>Race List
<a href='/races/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/race/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($races->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($races as $race)
	<tr>
			<td>
					<a href='{{ URL::to('races/'. $race->race_code . '/edit') }}'>
						{{$race->race_name}}
					</a>
			</td>
			<td>
					{{$race->race_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('races/delete/'. $race->race_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $races->appends(['search'=>$search])->render() }}
	@else
	{{ $races->render() }}
@endif
<br>
@if ($races->total()>0)
	{{ $races->total() }} records found.
@else
	No record found.
@endif
@endsection
