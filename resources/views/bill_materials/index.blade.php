@extends('layouts.app2')

@section('content')
<h3>{{ $product->product_name }}</h3>
<br>

@if ($bill_materials->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Code</th>
    <th>Product</th>
    <th>Quantity</th> 
    <th>Unit Price</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($bill_materials as $bill_material)
	<tr>
			<td>
					{{ $bill_material->bom_product_code }}
			</td>
			<td>
					<a href='{{ URL::to('bill_materials/'. $bill_material->id . '/edit') }}'>
						{{$bill_material->product->product_name}}
					</a>
			</td>
			<td>
					{{ floatval($bill_material->bom_quantity) }} 
					@if ($bill_material->unit)
							{{ $bill_material->unit->unit_shortname }}
					@endif
			</td>
			<td>
					{{ number_format($bill_material->unitPrice($bill_material->bom_product_code, $bill_material->unit_code),2) }}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('bill_materials/delete/'. $bill_material->id) }}'>
						<span class='glyphicon glyphicon-minus'></span>
					</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $bill_materials->appends(['search'=>$search])->render() }}
	@else
	{{ $bill_materials->render() }}
@endif
<br>
@if ($bill_materials->total()>0)
	{{ $bill_materials->total() }} records found.
@else
	No record found.
@endif
@endsection
