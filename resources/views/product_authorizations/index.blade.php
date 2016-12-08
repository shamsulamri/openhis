@extends('layouts.app')

@section('content')
<h1>Product Authorization List<a href='/product_authorizations/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></a></h1>
<form action='/product_authorization/search' method='post'>
	<div class='input-group'>
	<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if ($product_authorizations->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Authorization</th>
    <th>Category</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($product_authorizations as $product_authorization)
	<tr>
			<td>
					<a href='{{ URL::to('product_authorizations/'. $product_authorization->id . '/edit') }}'>
						{{$product_authorization->authorization->author_name}}
					</a>
			</td>
			<td>
					{{$product_authorization->category->category_name}}
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
