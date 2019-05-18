@extends('layouts.app')

@section('content')
<h1>Reorder Enquiry</h1>
<form id='form' action='/products/reorder' method='post' class='form-horizontal'>
	<div class="row">
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'><div align='left'>Product</div></label>
						<div class='col-sm-9'>
							<input type='text' class='form-control' placeholder="Name or code" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'>Store</label>
						<div class='col-sm-9'>
							{{ Form::select('store_code', $store, $store_code, ['class'=>'form-control','maxlength'=>'10']) }}
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'><div align='left'>Category</div></label>
						<div class='col-sm-9'>
							{{ Form::select('category_code', $categories, $category_code, ['class'=>'form-control','maxlength'=>'10']) }}
						</div>
					</div>
			</div>
	</div>
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
    <th><div align='right'>Store</div></th> 
    <th width='100'><div align='right'>On Hand</div></th> 
    <th width='50'><div align='right'>Min</div></th> 
    <th width='50'><div align='right'>Max</div></th> 
    <th width='100'><div align='right'>On Purchase</div></th> 
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
				{{ $product->limit_min }}
			</td>
			<td align='right'>
				{{ $product->limit_max?:'-' }}
			</td>
			<td align='right'>
				{{ $product->on_purchase?:'-' }}
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
<script>
		function search_now(value) {
				document.getElementById('export_report').value = value;
				document.getElementById('form').submit();
		}
</script>
@endsection
