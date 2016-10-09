@extends('layouts.app')

@section('content')
<h1>Product Status List</h1>
<br>
<form action='/product_status/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/product_statuses/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($product_statuses->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($product_statuses as $product_status)
	<tr>
			<td>
					<a href='{{ URL::to('product_statuses/'. $product_status->status_code . '/edit') }}'>
						{{$product_status->status_name}}
					</a>
			</td>
			<td>
					{{$product_status->status_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('product_statuses/delete/'. $product_status->status_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $product_statuses->appends(['search'=>$search])->render() }}
	@else
	{{ $product_statuses->render() }}
@endif
<br>
@if ($product_statuses->total()>0)
	{{ $product_statuses->total() }} records found.
@else
	No record found.
@endif
@endsection
