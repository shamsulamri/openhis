@extends('layouts.app')

@section('content')
<h1>Reorder Enquiry</h1>
<form action='/products/reorder' method='post' class='form-inline'>
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
    <th width='50'><div align='right'>Min</div></th> 
    <th width='50'><div align='right'>Max</div></th> 
    <th width='100'><div align='right'>On Purchase</div></th> 
    <th width='100'><div align='right'>On Transfer</div></th> 
    <th width='100'><div align='right'>In Transfer</div></th> 
	</tr>
  </thead>
	<tbody>
@foreach ($products as $product)
<?php
$on_hand=0;
$allocated=0;
if ($store_code == '') $store_code = null;
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
				@if ($product->limit_min)
				{{ $product->limit_min }}
				@endif
			</td>
			<td align='right'>
				@if ($product->limit_min)
				{{ $product->limit_max }}
				@endif
			</td>
			<td align='right'>
				{{ floatval($stock_helper->onPurchase($product->product_code)) }}
			</td>
			<td align='right'>
            	{{ floatval($stock_helper->onTransfer($product->product_code, $product->store_code)) }}
			</td>
			<td align='right'>
            	{{ floatval($stock_helper->inTransfer($product->product_code, $product->store_code)) }}
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
