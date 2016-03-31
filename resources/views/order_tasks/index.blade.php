@extends('layouts.app')

@section('content')
@include('patients.id')
<h2>Order Task</h1>
<br>
<form action='/order_task/status' method='post'>
	<div class="row">
			<div class="col-xs-6">
					<button class="btn btn-primary" type="submit" value="Submit">Update Status</button>
					<a class='btn btn-primary' href='/orders'>Edit Orders</a>
			</div>
			<div align="right" class="col-xs-6">
			</div>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
@if ($order_tasks->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th></th>
    <th>Product</th>
    <th>Ordered By</th>
    <th>Date</th>
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($order_tasks as $order)

	<?php $status='' ?>
	@if ($order->order_completed==1) 
			<?php $status='success' ?>
	@endif
	@if (isset($order->cancel_id)) 
			<?php $status='danger' ?>
	@endif
	<tr class='{{ $status }}'>
			<td width='10'>
					@if (!isset($order->cancel_id))
					{{ Form::checkbox($order->order_id, 1, $order->order_completed) }}
					@endif
			</td>
			<td>
					@if (!isset($order->cancel_id))
					<a href='{{ URL::to('order_tasks/'. $order->order_id . '/edit') }}'>
					{{$order->product_name}}
					</a>
					@else
					CANCEL: {{$order->product_name}}
					@endif
			</td>
			<td>
					{{ $order->name }}
			</td>
			<td>
					{{ date('d F, H:i', strtotime($order->created_at)) }}
			</td>
			<td align='right'>
					@if (!isset($order->cancel_id))
					<a class='btn btn-primary btn-xs' href='#'>Print Label</a>
					@endif
					@if ($order->order_completed==0 && !isset($order->cancel_id))
					<a class='btn btn-warning btn-xs' href='{{ URL::to('/task_cancellations/create/'. $order->order_id) }}'>Cancel</a>
					@endif
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
	{{ Form::hidden('ids', $ids) }}
</form>
@if (isset($search)) 
	{{ $order_tasks->appends(['search'=>$search])->render() }}
	@else
	{{ $order_tasks->render() }}
@endif
<br>
@if ($order_tasks->total()>0)
	{{ $order_tasks->total() }} records found.
@else
	No record found.
@endif
@endsection
