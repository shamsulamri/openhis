@extends('layouts.app')

@section('content')
<h1>Product Authorization Index</h1>
<br>
<form action='/product_authorization/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<br>
<a href='/product_authorizations/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($product_authorizations->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>author_id</th>
    <th>id</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($product_authorizations as $product_authorization)
	<tr>
			<td>
					<a href='{{ URL::to('product_authorizations/'. $product_authorization->id . '/edit') }}'>
						{{$product_authorization->author_id}}
					</a>
			</td>
			<td>
					{{$product_authorization->category_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('product_authorizations/delete/'. $product_authorization->id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $product_authorizations->appends(['search'=>$search])->render() }}
	@else
	{{ $product_authorizations->render() }}
@endif
<br>
@if ($product_authorizations->total()>0)
	{{ $product_authorizations->total() }} records found.
@else
	No record found.
@endif
@endsection
