@extends('layouts.app')

@section('content')
<h1>Product Category List</h1>
<br>
<form action='/product_category/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/product_categories/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($product_categories->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Code</th> 
    <th>Name</th>
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($product_categories as $product_category)
	<tr>
			<td>
					{{$product_category->category_code}}
			</td>
			<td>
					<a href='{{ URL::to('product_categories/'. $product_category->category_code . '/edit') }}'>
						{{$product_category->category_name}}
					</a>
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('product_categories/delete/'. $product_category->category_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $product_categories->appends(['search'=>$search])->render() }}
	@else
	{{ $product_categories->render() }}
@endif
<br>
@if ($product_categories->total()>0)
	{{ $product_categories->total() }} records found.
@else
	No record found.
@endif
@endsection
