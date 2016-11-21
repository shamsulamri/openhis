@extends('layouts.app')

@section('content')
<h1>Product List<a href='/products/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></a></h1>
<br>
<form action='/product/search' method='post'>
	<div class='input-group'>
	<input type='text' class='form-control' placeholder="Enter product name or code" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($products->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Code</th> 
    <th>Name</th>
    <th><div align='right'>On Hand</div></th> 
	@can('system-administrator')
	<th></th>
	@endcan
	</tr>
  </thead>
	<tbody>
@foreach ($products as $product)
	<tr>
			<td width='100'>
					{{$product->product_code}}
			</td>
			<td>
					<a href='{{ URL::to('products/'. $product->product_code . '/edit') }}'>
						{{$product->product_name}}
						@if ($product->product_bom==1)
							*
						@endif
					</a>
			</td>
			<td align='right'>
				@if ($product->product_on_hand>0)
					{{ str_replace('.00','',$product->product_on_hand) }}
				@endif
			</td>
			@can('system-administrator')
			<td align='right'>
				<!--
					@if ($product->product_bom==1)
					<a class='btn btn-default btn-xs' href='{{ URL::to('bill_materials/'. $product->product_code) }}'>Bill of Materials</a>
					@endif
					@if ($product->category_code=='assembly')
					<a class='btn btn-default btn-xs' href='{{ URL::to('build_assembly/'. $product->product_code) }}'>Build Assemblies</a>
					@endif
					@if ($product->category_code=='drugs')
					<a class='btn btn-default btn-xs' href='{{ URL::to('drug_prescriptions/'. $product->product_code.'/edit') }}'><i class='fa fa-user-md' aria-hidden='true'></i>
</a>
					@endif
					@if ($product->product_stocked==1)
					<a class='btn btn-default btn-xs' href='{{ URL::to('stocks/'. $product->product_code) }}'><span class='glyphicon glyphicon-road' aria-hidden='true'></span></a>
					@endif
				-->
					<a class='btn btn-danger btn-xs' href='{{ URL::to('products/delete/'. $product->product_code) }}'>Delete</a>
			</td>
			@endcan
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $products->appends(['search'=>$search])->render() }}
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
