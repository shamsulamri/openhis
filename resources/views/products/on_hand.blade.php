@extends('layouts.app')

@section('content')
<h1>Stock On Hand Enquiry</h1>
<form id='form' action='/products/on_hand' method='post' class='form-horizontal'>
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
						<label class='col-sm-3 control-label'><div align='left'>Batch</div></label>
						<div class='col-sm-9'>
							<input type='text' class='form-control' name='batch_number' value='{{ isset($batch_number) ? $batch_number : '' }}' autocomplete='off' autofocus>
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

@if (!empty($products))
@if ($products->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th> 
    <th>Batch</th> 
    <th>Store</th> 
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
					<a href='{{ URL::to('products/on_hand?search='. $product->product_code) }}'>
						{{ $product->product_name }} 
						@if ($product->unit_shortname) 
						({{ $product->unit_shortname }}) 
						@endif
					</a>
					<br>
					{{$product->product_code}}
			</td>
			<td>
				{{ $product->inv_batch_number?:'-' }}
			</td>
			<td>
				{{ $product->store_name }}
			</td>
			<td align='right'>
				{{ $product->on_hand }}
			</td>
			<td align='right'>
				{{ $product->allocated }}
			</td>
			<td align='right'>
				{{ $product->available }}
			</td>
			<td align='right'>
				{{ number_format($product->average_cost,2) }}
			</td>
			<td align='right'>
				{{ number_format($product->total_cost,2) }}
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
{{ $products->appends(['search'=>$search,'batch_number'=>$batch_number, 'store'=>$store_code])->render() }}
<br>
@if ($products->total()>0)
	{{ $products->total() }} records found.
@else
	No record found.
@endif
@endif
<script>
		function search_now(value) {
				document.getElementById('export_report').value = value;
				document.getElementById('form').submit();
		}
</script>
@endsection
