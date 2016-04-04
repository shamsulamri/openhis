@extends('layouts.app')

@section('content')	
@include('orders.panel')

@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif

@if (Auth::user()->authorization->author_consultation<>1)
        <a class="btn btn-default" href="/order_tasks/task/{{ Session::get('encounter_id') }}/{{Cookie::get('queue_location')}}" role="button">Back to Task</a>
@endif

<a href='/order_products' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($orders->total()>0)
<table class="table table-hover">
	<tbody>
@foreach ($orders as $order)
	<?php $status='' ?>
	@if ($order->order_is_discharge==1) 
			<?php $status='warning' ?>
	@endif
	@if ($order->order_completed==1) 
			<?php $status='success' ?>
	@endif
	<tr class='{{ $status }}'>
			<td class='col-xs-2'>
					{{ date('d F, H:i', strtotime($order->created_at)) }}
			</td>
			<td>
			@if (isset($order->cancel_id)) 
					<a href='{{ URL::to('order_cancellations/'. $order->cancel_id) }}'>
					<strike>	{{ ucfirst(strtoupper($order->product_name)) }}</strike>
					</a>
			@elseif ($order->order_completed==0) 
					@if ($order->post_id>0)
						<a href='{{ URL::to('orders/'. $order->order_id . '/show') }}'>
					@elseif ($order->post_id==0)
						<a href='{{ URL::to('orders/'. $order->order_id . '/edit') }}'>
					@endif
						{{ ucfirst(strtoupper($order->product_name)) }}
					</a>
			@else
					<a href='{{ URL::to('orders/'. $order->order_id . '/show') }}'>
						{{ ucfirst(strtoupper($order->product_name)) }}
					</a>
			@endif
			</td>
			<td align='right'>
				@if ($order->order_completed==0) 
					@if ($order->post_id>0 && $order->order_is_discharge==0)
						@if (!isset($order->cancel_id))
							<a class='btn btn-warning btn-xs' href='{{ URL::to('/order_cancellations/create/'. $order->order_id) }}'>Cancel</a>
						@endif
					@else
						@if (!isset($order->cancel_id))
							<a class='btn btn-danger btn-xs' href='{{ URL::to('orders/delete/'. $order->order_id) }}'>Delete</a>
						@endif
					@endif
				@else
					@if ($order->order_report != '')
					<a class='btn btn-default btn-xs' href='{{ URL::to('orders/'. $order->order_id .'/show') }}'>View Report</a>
					@endif
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
