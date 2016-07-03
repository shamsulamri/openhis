@extends('layouts.app2')

@section('content')
<h3>{{ $product->product_name }}</h3>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
@if ($bill_materials->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Product</th>
    <th>Quantity</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($bill_materials as $bill_material)
	<tr>
			<td>
					<a href='{{ URL::to('bill_materials/'. $bill_material->id . '/edit') }}'>
						{{$bill_material->product_name}}
					</a>
			</td>
			<td>
					{{ str_replace('.00','',$bill_material->bom_quantity) }} {{ $bill_material->unit_shortname }}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('bill_materials/delete/'. $bill_material->id) }}'>-</a>
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
