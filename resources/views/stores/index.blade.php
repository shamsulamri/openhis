@extends('layouts.app')

@section('content')
<h1>Store List<a href='/stores/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></a></h1>
<form action='/store/search' method='post'>
	<div class='input-group'>
	<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if ($stores->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($stores as $store)
	<tr>
			<td>
					<a href='{{ URL::to('stores/'. $store->store_code . '/edit') }}'>
						{{$store->store_name}}
					</a>
			</td>
			<td>
					{{$store->store_code}}
			</td>
			<td align='right'>
			@can('system-administrator')
					<a class='btn btn-danger btn-xs' href='{{ URL::to('stores/delete/'. $store->store_code) }}'>Delete</a>
			@endcan
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $stores->appends(['search'=>$search])->render() }}
	@else
	{{ $stores->render() }}
@endif
<br>
@if ($stores->total()>0)
	{{ $stores->total() }} records found.
@else
	No record found.
@endif
@endsection
