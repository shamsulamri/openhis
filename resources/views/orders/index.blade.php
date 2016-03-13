@extends('layouts.app')

@section('content')	
@include('patients.label')
@include('consultations.panel')
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/order_products/{{ $consultation->consultation_id }}' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($orders->total()>0)
<table class="table table-hover">
	<tbody>
@foreach ($orders as $order)
	<?php $status='' ?>
	@if (isset($order->cancel_id)) 
			<?php $status='danger' ?>
	@endif
	@if ($order->order_is_discharge==1) 
			<?php $status='success' ?>
	@endif
	<tr class='{{ $status }}'>
			<td>
					{{ date('d F, H:i', strtotime($order->created_at)) }}
			</td>
			<td>
					@if (!isset($order->cancel_id))
					<a href='{{ URL::to('orders/'. $order->order_id . '/edit') }}'>
					@else
					<a href='{{ URL::to('order_cancellations/'. $order->cancel_id) }}'>
					CANCEL: 
					@endif
						{{ ucfirst(strtoupper($order->product_name)) }}
					</a>
			</td>
			<td>
					{{$order->product_code}}
			</td>
			<td align='right'>
					@if ($order->post_id>0 && $order->order_is_discharge==0)
						@if (!isset($order->cancel_id))
							<a class='btn btn-warning btn-xs' href='{{ URL::to('/order_cancellations/create/'. $order->order_id) }}'>Cancel</a>
						@endif
					@else
					<a class='btn btn-danger btn-xs' href='{{ URL::to('orders/delete/'. $order->order_id) }}'>Delete</a>
					@endif
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $orders->appends(['search'=>$search])->render() }}
	@else
	{{ $orders->render() }}
@endif
<br>
@if ($orders->total()>0)
	{{ $orders->total() }} records found.
@else
	No record found.
@endif
@endsection
