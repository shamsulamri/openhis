@extends('layouts.app')

@section('content')
<h1>Product List<a href='/products/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></a></h1>
@if ($store)
<h3>{{ $store->store_name }}</h3>
@endif
<br>
<form action='/product/search' method='post' class='form-inline'>
	<label>Product</label>
	<div class="form-group">
			<input type='text' class='form-control' placeholder="Name or code" id='search' name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
   </div>
	<label>Category</label>
	{{ Form::select('category_code', $categories, $category_code, ['class'=>'form-control','maxlength'=>'10']) }}
	<button class="btn btn-primary" type="submit" value="Submit">Search</button>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">


</form>
<br>
@if ($products->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Code</th> 
    <th>Name</th> 
    <th>Other</th> 
    <th>Category</th> 
    <th>UOM</th> 
    <th><div align='right'>On Hand</div></th> 
	@can('system-administrator')
	<th></th>
	@endcan
	</tr>
  </thead>
	<tbody>
@foreach ($products as $product)
<?php
$on_hand=0;
$allocated=0;
?>
	<tr>
			<td>
					{{$product->product_code}}
			</td>
			<td>
					@can('product_information_edit')
					<a tabindex='10' href='{{ URL::to('products/'. $product->product_code . '/edit') }}'>
					@else
					<a tabindex='10' href='{{ URL::to('products/'. $product->product_code.'?detail=true') }}'>
					@endcan
						{{ $product->product_name }} 
					</a>
			</td>
			<td>
					{{ $product->product_name_other?:"-" }}
			</td>
			<td>
					@if ($product->category)
					{{ $product->category->category_name }}
					@endif
			</td>
			<td>
						@if ($product->product) 
							{{ $product->product->unitMeasure->unit_shortname }}
						@else
							@if ($product->unitMeasure)
									{{ $product->unitMeasure->unit_shortname }}
							@endif
						@endif
			</td>
			<td align='right'>
					{{ $helper->getStockOnHand($product->product_code, $store?$store->store_code:null) }}
			</td>
			@can('system-administrator')
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('products/delete/'. $product->product_code) }}'>Delete</a>
			</td>
			@endcan
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search) | isset($category_code)) 
	{{ $products->appends(['search'=>$search,'category_code'=>$category_code])->render() }}
	@else
	{{ $products->render() }}
@endif
<br>
@if ($products->total()>0)
	{{ $products->total() }} records found.
@else
	No record found.
@endif

@endsection
