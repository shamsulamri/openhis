@extends('layouts.app')

@section('content')
<h1>Stock On Hand Enquiry</h1>
<form action='/products/on_hand' method='post' class='form-inline'>
	<label>Product</label>
	<input type='text' class='form-control' placeholder="Name or code" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<label>Category</label>
	{{ Form::select('category_code', $categories, $category_code, ['class'=>'form-control','maxlength'=>'10']) }}
	<label>Store</label>
	{{ Form::select('store', $store, $store_code, ['class'=>'form-control','maxlength'=>'10']) }}
	<button class="btn btn-primary" type="submit" value="Submit">Search</button>
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
						@if ($product->product) 
							({{ $product->product->unitMeasure->unit_shortname }}) 
						@else
							@if ($product->unitMeasure)
							({{ $product->unitMeasure->unit_shortname }}) 
							@endif
						@endif
						@if ($product->product_bom==1)
							*
						@endif
					</a>
					<br>
					{{$product->product_code}}
			</td>
			<td align='right'>
				@if ($product->store)
				{{ $product->store->store_name }}
				@else
					-
				@endif
			</td>
			<td align='right'>
			@if	($product->product_stocked==1)
				<?php $on_hand = $stock_helper->getStockCountByStore($product->product_code, $product->store_code); ?>
				{{ floatval($on_hand) }}
			@else
					-
			@endif
			</td>
			<td align='right'>
				<?php $allocated = $stock_helper->getStockAllocatedByStore($product->product_code, $product->store_code); ?>
				{{ $allocated }}
			</td>
			<td align='right'>
					{{ $on_hand - $allocated }}
			</td>
			<td align='right'>
					{{$product->product_average_cost}}
			</td>
			<td align='right'>
					{{ number_format($product->product_average_cost*$on_hand,2) }}
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search) | isset($category_code) | isset($store_code)) 
	{{ $products->appends(['search'=>$search,'category_code'=>$category_code, 'store'=>$store_code])->render() }}
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
