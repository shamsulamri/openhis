@extends('layouts.app')

@section('content')
<h1>Marital Status List
<a href='/marital_statuses/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/marital_status/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($marital_statuses->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($marital_statuses as $marital_status)
	<tr>
			<td>
					<a href='{{ URL::to('marital_statuses/'. $marital_status->marital_code . '/edit') }}'>
						{{$marital_status->marital_name}}
					</a>
			</td>
			<td>
					{{$marital_status->marital_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('marital_statuses/delete/'. $marital_status->marital_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $marital_statuses->appends(['search'=>$search])->render() }}
	@else
	{{ $marital_statuses->render() }}
@endif
<br>
@if ($marital_statuses->total()>0)
	{{ $marital_statuses->total() }} records found.
@else
	No record found.
@endif
@endsection
