@extends('layouts.app')

@section('content')
<h1>Store Authorization List<a href='/store_authorizations/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></a></h1>
<form action='/store_authorization/search' method='post'>
	<div class='input-group'>
	<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if ($store_authorizations->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Authorization</th>
    <th>Category</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($store_authorizations as $store_authorization)
	<tr>
			<td>
					<a href='{{ URL::to('store_authorizations/'. $store_authorization->id . '/edit') }}'>
						{{$store_authorization->authorization->author_name}}
					</a>
			</td>
			<td>
					{{$store_authorization->store->store_name}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('store_authorizations/delete/'. $store_authorization->id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $store_authorizations->appends(['search'=>$search])->render() }}
	@else
	{{ $store_authorizations->render() }}
@endif
<br>
@if ($store_authorizations->total()>0)
	{{ $store_authorizations->total() }} records found.
@else
	No record found.
@endif
@endsection
