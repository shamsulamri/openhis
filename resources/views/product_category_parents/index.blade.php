@extends('layouts.app')

@section('content')
<h1>Product Category Parent Index
<a href='/product_category_parents/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/product_category_parent/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($product_category_parents->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>parent_code</th>
    <th>parent_code</th> 
	@can('system-administrator')
	<th></th>
	@endcan
	</tr>
  </thead>
	<tbody>
@foreach ($product_category_parents as $product_category_parent)
	<tr>
			<td>
					<a href='{{ URL::to('product_category_parents/'. $product_category_parent->parent_code . '/edit') }}'>
						{{$product_category_parent->parent_code}}
					</a>
			</td>
			<td>
					{{$product_category_parent->parent_code}}
			</td>
			@can('system-administrator')
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('product_category_parents/delete/'. $product_category_parent->parent_code) }}'>Delete</a>
			</td>
			@endcan
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $product_category_parents->appends(['search'=>$search])->render() }}
	@else
	{{ $product_category_parents->render() }}
@endif
<br>
@if ($product_category_parents->total()>0)
	{{ $product_category_parents->total() }} records found.
@else
	No record found.
@endif
@endsection
