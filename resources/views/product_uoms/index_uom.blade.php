@extends('layouts.app')

@section('content')
@include('products.id')
<h1>Store Keeping Unit

<a href='/product_uoms/create?id={{$product->product_code}}' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
@if ($product_uoms->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Unit</th>
    <th>Rate</th> 
    <th>Cost</th> 
    <th>Price 1<br><small>(Outpatient/Public)</small></th>
    <th>Price 2<br><small>(Inpatient/Sponsor)</small></th>
	<th><div align='center'>Default Cost</div></th> 
	<th><div align='center'>Default Price</div></th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($product_uoms as $product_uom)
	<tr>
			<td>
				@if ($product_uom->unitMeasure)
					<a href='{{ URL::to('product_uoms/'. $product_uom->id . '/edit') }}'>
						{{$product_uom->unitMeasure->unit_name}}
					</a>
				@else
					{{ $product_oum->unit_code }}	
				@endif
			</td>
			<td>
					{{$product_uom->uom_rate}}
			</td>
			<td>
					{{number_format($product_uom->uom_cost,2)}}
			</td>
			<td>
					{{number_format($product_uom->uom_price,2)}}
			</td>
			<td>
					{{number_format($product_uom->uom_price_2,2)}}
			</td>
			<td align='center' width='150'>
					{{$product_uom->uom_default_cost?'X':''}}
			</td>
			<td align='center' width='150'>
					{{$product_uom->uom_default_price?'X':''}}
			</td>
			<td align='right'>
				@can('system-administrator')
					<a class='btn btn-danger btn-xs' href='{{ URL::to('product_uoms/delete/'. $product_uom->id) }}'>Delete</a>
				@endcan
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $product_uoms->appends(['search'=>$search])->render() }}
	@else
	{{ $product_uoms->render() }}
@endif
<br>
@if ($product_uoms->total()>0)
	{{ $product_uoms->total() }} records found.
@else
	No record found.
@endif
@endsection
