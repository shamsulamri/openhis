@extends('layouts.app')

@section('content')
<h1>Order Task Index</h1>
<br>
@include('orders.panel')
<form action='/order_task/status' method='post'>
	<button class="btn btn-primary" type="submit" value="Submit">Update</button>
	<button class="btn btn-primary">Print All</button>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
<br>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
@if ($order_tasks->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th></th>
    <th></th>
    <th></th>
    <th>Product</th>
    <th>Source</th> 
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
			<td width='70'>
					@if (!isset($order->cancel_id))
					{{ Form::select('prints', array(''=>'','1'=>'1','2'=>'2','3'=>'3'),'1',['class'=>'form-control']) }}
					@endif
			</td>
			<td>
					@if (!isset($order->cancel_id))
					<a class='btn btn-primary' href='#'>Print</a>
					@endif
			</td>
			<td width='30'>
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
					{{$order->location_name}} 			
			</td>
			<td align='right'>
					@if ($order->order_completed==0 && !isset($order->cancel_id))
					<a class='btn btn-warning' href='{{ URL::to('/task_cancellations/create/'. $order->order_id) }}'>Cancel</a>
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
