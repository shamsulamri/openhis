@extends('layouts.app')

@section('content')
<h1>Stock On Hand Enquiry</h1>
<form id='form' action='/products/on_hand' method='post' class='form-inline'>
	<label>Product</label>
	<input type='text' class='form-control' placeholder="Name or code" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<label>Category</label>
	{{ Form::select('category_code', $categories, $category_code, ['class'=>'form-control','maxlength'=>'10']) }}
	<label>Store</label>
	{{ Form::select('store', $store, $store_code, ['class'=>'form-control','maxlength'=>'10']) }}
	<a href='#' onclick='javascript:search_now(0);' class='btn btn-primary'>Search</a>
	<a href='#' onclick='javascript:search_now(1);' class='btn btn-primary pull-right'><span class='fa fa-print'></span></a>
	<input type='hidden' id='export_report' name="export_report">
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($products->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th> 
    <th width='100'><div align='right'>Store</div></th> 
    <th width='100'><div align='right'>On Hand</div></th> 
    <th width='50'><div align='right'>Allocated</div></th> 
    <th width='50'><div align='right'>Available</div></th> 
    <th width='100'><div align='right'>Average Cost</div></th> 
    <th width='100'><div align='right'>Total Cost</div></th> 
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
					@can('product_information_edit')
					<a href='{{ URL::to('products/'. $product->product_code . '/edit') }}'>
					@else
					<a href='{{ URL::to('products/'. $product->product_code.'?detail=true') }}'>
					@endcan
						{{ $product->product_name }} 
						({{ $product->unit_shortname }}) 
					</a>
					<br>
					{{$product->product_code}}
			</td>
			<td align='right'>
				{{ $product->store_name }}
			</td>
			<td align='right'>
				{{ floatval($product->stock_quantity) }}
			</td>
			<td align='right'>
				{{ floatval($product->allocated) }}
			</td>
			<td align='right'>
				{{ floatval($product->available) }}
			</td>
			<td align='right'>
					{{$product->product_average_cost}}
			</td>
			<td align='right'>
					{{ number_format($product->total_cost,2) }}
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
{{ $products->appends(['search'=>$search,'category_code'=>$category_code, 'store'=>$store_code])->render() }}
<br>
@if ($products->total()>0)
	{{ $products->total() }} records found.
@else
	No record found.
@endif
<script>
		function search_now(value) {
				document.getElementById('export_report').value = value;
				document.getElementById('form').submit();
		}
</script>
@endsection
