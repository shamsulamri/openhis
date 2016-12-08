@extends('layouts.app')

@section('content')
<h1>Product Category List
<a href='/product_categories/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/product_category/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
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
