@extends('layouts.app')

@section('content')
@include('products.id')
<h1>Product Maintenance List</h1>
<br>
<a class="btn btn-default" href="/products/{{ $product->product_code }}/option" role="button">Back</a>
<a href='/product_maintenances/create?product_code={{ $product->product_code }}' class='btn btn-primary'>Create</a>
<form action='/product_maintenance/search' method='post'>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
@if ($product_maintenances->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Date</th> 
    <th>Reason</th>
    <th>Description</th>
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($product_maintenances as $product_maintenance)
	<tr>
			<td width='200'>
					{{ date('d F Y, H:i', strtotime($product_maintenance->maintain_datetime)) }}
			</td>
			<td>
					<a href='{{ URL::to('product_maintenances/'. $product_maintenance->maintain_id . '/edit') }}'>
						{{$product_maintenance->reason_name}}
					</a>
			</td>
			<td>
					{{ $product_maintenance->maintain_description }}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('product_maintenances/delete/'. $product_maintenance->maintain_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $product_maintenances->appends(['search'=>$search])->render() }}
	@else
	{{ $product_maintenances->render() }}
@endif
<br>
@if ($product_maintenances->total()>0)
	{{ $product_maintenances->total() }} records found.
@else
	No record found.
@endif
@endsection
