@extends('layouts.app')

@section('content')
@include('patients.id_only')
<h1>Order Task</h1>
<h3>{{ $location->location_name }}</h3>
@if (!empty($location->store))
<h5>{{ $location->store->store_name }}</h5>
@endif
<br>
<form action='/order_task/status' method='post'>
	<div class="row">
			<div class="col-xs-6">
					<button class="btn btn-primary" type="submit" value="Submit">Update Status</button>
					@can('module-order')
					<!--
					<a class='btn btn-primary' href='/orders/make'>Edit Orders</a>
					-->
					@endcan
			</div>
			<div align="right" class="col-xs-6">
					<a class="btn btn-primary pull-right" href="{{ Config::get('host.report_server') }}/ReportServlet?report=drug_label&id={{ $encounter->encounter_id }}" target="_blank" role="button"><span class='glyphicon glyphicon-print'></span>
 Label</a>
			</div>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">

@if ($order_tasks->total()>0)
<table class="table">
 <thead>
	<tr> 
    <th></th>
    <th>Product</th>
    <th>On Hand</th>
    <th>Allocated</th>
    <th>Available</th>
    <th>Quantity</th>
    <th>Orderer</th>
    <th>Date</th>
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($order_tasks as $order)

	<?php 
	$status='';
	$allocated =  $stock_helper->getStockAllocatedByStore($order->product_code, $order->store_code, $encounter_id); //-$order->order_quantity_request;
	$on_hand = $stock_helper->getStockCountByStore($order->product_code, $location->store_code);
	$available = $on_hand-$allocated;
	$batches = $helper->getBatches($order->product_code)?:null;
	$quantity_request = $order->order_quantity_request;
	?>
	@if ($order->order_completed==1) 
			<?php $status='success' ?>
	@endif
	@if ($order->product_stocked)
						@if ($on_hand-$allocated<$order->order_quantity_request)
								<?php $status = 'danger'; ?>
						@endif
	@endif
	<tr class='{{ $status }}'>
			<td width='10'>
					@if (!isset($order->cancel_id) && $order->order_completed==0)
					{{ Form::checkbox($order->order_id, 1, $order->order_completed,['class'=>'i-checks']) }}
					@endif
			</td>
			<td>
					@if (!isset($order->cancel_id))
							<a href='{{ URL::to('order_tasks/'. $order->order_id . '/edit') }}'>
							{{$order->product_name}}
							</a>
					@else
							<strike>
							{{$order->product_name}}
							</strike>
					@endif
					<br>{{$order->product_code}}

					@if ($order->category_code=='drugs')
					<br>
						{{ $order_helper->getPrescription($order->order_id) }}
					@endif

					@if ($status=='danger')
						<br>
						<span class='label label-danger'>Insufficient supply.</span>
					@endif
			</td>
			<td>
			@if ($order->product_stocked)
					{{ $on_hand }} 
			@else 
					-
			@endif
			</td>
			<td>
			@if ($order->product_stocked)
					{{ $allocated }} 
			@else 
					-
			@endif
			</td>
			<td>
			@if ($order->product_stocked)
					{{ $available }}
			@else 
					-
			@endif
			</td>
			<td width='100'>
					@if ($order->product_stocked==1)
						@if ($order->order_completed==0) 
							{{ Form::text('quantity_'.$order->order_id, $order->order_quantity_request, ['class'=>'form-control']) }}
						@else
							{{ $order->order_quantity_request }}
							{{ Form::hidden('quantity_'.$order->order_id, $order->order_quantity_request) }}
						@endif
					@else
						@if (!$batches)
							{{ Form::text('quantity_'.$order->order_id, $order->order_quantity_request, ['class'=>'form-control']) }}
						@else
							{{ $order->order_quantity_request }} 
							{{ Form::hidden('quantity_'.$order->order_id, $order->order_quantity_request) }}
						@endif
					@endif
			</td>
			<td>
					{{ $order->name }}
					<br>
					{{ $order->ward_name}}
			</td>
			<td>
					{{ (DojoUtility::dateReadFormat($order->investigation_date)) }}
			</td>
			<td align='right'>
					@if (!isset($order->cancel_id))
					@endif
					@if ($order->order_completed==0 && !isset($order->cancel_id))
					<a class='btn btn-warning btn-xs' href='{{ URL::to('/task_cancellations/create/'. $order->order_id) }}'>Cancel</a>
					@endif
			</td>
	</tr>
		<table>
		@foreach ($batches as $batch)
			<?php
			$supply = 0;
			if ($batch->sum_quantity<$quantity_request) {
				$supply = $batch->sum_quantity;
				$quantity_request -= $batch->sum_quantity;
			}

			if ($batch->sum_quantity>$quantity_request) {
				$quantity_request = $batch->sum_quantity-$quantity_request;
				$supply = $quantity_request;
			}
			?>
			<tr>
				<td>
					{{ $batch->inv_batch_number }}
				</td>
				<td>
					{{ $batch->batch()->batch_expiry_date }}
				</td>
				<!--
				<td>
					{{ Form::select('batch_unit_'.$batch->batch()->batch_id, $helper->getUOM($batch->product_code),null, ['id'=>'gender_code','class'=>'form-control']) }}
				</td>
				-->
				<td>
            		{{ Form::text('batch_'.$batch->product_code.'_'.$batch->batch()->batch_id, $supply?:0, ['class'=>'form-control']) }}
				</td>
				<td>
					{{ $batch->sum_quantity }} {{ $batch->unit->unit_name }}
				</td>
			</tr>
		@endforeach
		</table>
@endforeach
@endif
</tbody>
</table>
	{{ Form::hidden('ids', $ids) }}
	{{ Form::hidden('encounter_id', $encounter_id) }}
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
