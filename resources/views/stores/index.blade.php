@extends('layouts.app')

@section('content')
<h1>Store List</h1>
<br>
<form action='/store/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/stores/create' class='btn btn-primary'>Create</a>
<br>
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
					<a class='btn btn-danger btn-xs' href='{{ URL::to('stores/delete/'. $store->store_code) }}'>Delete</a>
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
