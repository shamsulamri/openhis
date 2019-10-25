@extends('layouts.app')

@section('content')
<h1>Order Index
<a href='/order_maintenances/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/order_maintenance/search' method='post' class='form-horizontal'>
	<div class="row">
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-5 control-label'><div align='left'>Encounter Id</div></label>
						<div class='col-sm-7'>
							<input type='text' class='form-control' placeholder="" name='encounter_id' value='{{ isset($encounter_id) ? $encounter_id : '' }}' autocomplete='off' autofocus>
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-5 control-label'><div align='left'>Order Id</div></label>
						<div class='col-sm-7'>
							<input type='text' class='form-control' placeholder="" name='order_id' value='{{ isset($order_id) ? $order_id : '' }}' autocomplete='off' autofocus>
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-5 control-label'><div align='left'>Product Code</div></label>
						<div class='col-sm-7'>
							<input type='text' class='form-control' placeholder="" name='product_code' value='{{ isset($product_code) ? $product_code : '' }}' autocomplete='off' autofocus>
						</div>
					</div>
			</div>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
			<button type="submit" class="btn btn-md btn-primary">Search</button> 
</form>
<br>

@if ($orders->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Order Id</th> 
    <th>Encounter Id</th> 
    <th>Code</th>
    <th>Product</th>
    <th>Quantity</th>
	@can('system-administrator')
	<th></th>
	@endcan
	</tr>
  </thead>
	<tbody>
@foreach ($orders as $order)
	<tr>
			<td>
					<a href='{{ URL::to('order_maintenances/'. $order->order_id . '/edit') }}'>
						{{$order->order_id}}
					</a>
			</td>
			<td>
					{{$order->encounter_id}}
			</td>
			<td>
					{{$order->product_code}}
			</td>
			<td>
					{{$order->product->product_name}}
			</td>
			<td>
					{{$order->order_quantity_supply}}
			</td>
			@can('system-administrator')
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('order_maintenances/delete/'. $order->order_id) }}'>Delete</a>
			</td>
			@endcan
	</tr>
@endforeach
@endif
</tbody>
</table>
{{ $orders->appends(['encounter_id'=>$encounter_id, 'order_id'=>$order_id, 'product_code'=>$product_code])->render() }}
<br>
@if ($orders->total()>0)
	{{ $orders->total() }} records found.
@else
	No record found.
@endif
@endsection
