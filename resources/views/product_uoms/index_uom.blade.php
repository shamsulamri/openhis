@extends('layouts.app')

@section('content')
@include('products.id')
<h1>Unit of Measure

<a href='/product_uoms/create?id={{$product->product_code}}' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
@if ($product_uoms->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Unit</th>
    <th>Rate</th> 
    <th>Cost</th> 
    <th>Price</th>
    <th>Default Cost</th> 
    <th>Default Price</th>
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($product_uoms as $product_uom)
	<tr>
			<td>
					<a href='{{ URL::to('product_uoms/'. $product_uom->id . '/edit') }}'>
						{{$product_uom->unitMeasure->unit_name}}
					</a>
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
					{{$product_uom->uom_default_cost?'Default':''}}
			</td>
			<td>
					{{$product_uom->uom_default_price?'Default':''}}
			</td>
			<td align='right'>
				@can('system-administrator')
					@if ($product_uom->unit_code != 'unit')
					<a class='btn btn-danger btn-xs' href='{{ URL::to('product_uoms/delete/'. $product_uom->id) }}'>Delete</a>
					@endif
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
