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
				@if ($status != 'danger')
					@if (!isset($order->cancel_id) && $order->order_completed==0)
					{{ Form::checkbox($order->order_id, 1, $order->order_completed,['class'=>'i-checks']) }}
					@endif
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

					@if ($status=='danger' & $order->product_track_batch==0)
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
					@if ($order->product_track_batch==0 && $order->product_stocked==1)
						@if ($order->order_completed==0) 
							{{ Form::text('quantity_'.$order->order_id, $order->order_quantity_request, ['class'=>'form-control']) }}
						@else
							{{ $order->order_quantity_supply }}
							{{ Form::hidden('quantity_'.$order->order_id, $order->order_quantity_request) }}
						@endif
					@else
					{{ $order->order_quantity_supply }} 
					{{ Form::hidden('quantity_'.$order->order_id, $order->order_quantity_supply) }}
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
	@if ($order->product_track_batch && $order->order_completed==0)
<?php
	$batches = $stock_helper->getBatches($order->product_code, $location->store_code);
	$order_request = $order->order_quantity_request;
	$total_quantity = 0;
?>
			@if (count($batches)>0) 
			<tr>
				<td colspan=1>		
				</td>
				<td colspan=2>
						<table class='table'>
							<thead>
							<tr>
								<th>Batch Number</th>
								<th>Expiry Date<div></th>
								<th width='20%'>On-Hand</th>
								<th width='20%'>Quantity<div></th>
							</tr>
							</thead>
					@foreach ($batches as $batch)	
						<?php
						$allocated = 0;
						if ($order_request>$batch->batch_quantity) {
							$allocated = $batch->batch_quantity;
						} else {
							$allocated = $order_request-$total_quantity;
						}
						if ($allocated+$total_quantity>$order_request) $allocated = $order_request-$total_quantity;
						$total_quantity += $allocated;
						?>
							<tr>
								<td>
										{{ Form::label('stock_quantity', $batch->batch_number, ['class'=>'form-control']) }}
								</td>
								<td>
										{{ Form::label('stock_quantity', $batch->expiry_date, ['class'=>'form-control']) }}
								</td>
								<td>
										{{ Form::label('batch_quantity', $batch->batch_quantity, ['class'=>'form-control']) }}
								</td>
								<td>
										{{ Form::text($batch->product_code.'_'.$batch->batch_number, $allocated, ['class'=>'form-control']) }}
								</td>
							</tr>
					@endforeach
						@if ($total_quantity<$order->order_quantity_request)
						<tr>
							<td colspan=4>
								<div class='alert alert-danger'>
									<strong>Warning !</strong> Insufficient supply ({{ $order_request-$total_quantity }})
								</div>
							</td>
						</tr>
						@endif
						</table>
				</td>
				<td colspan=6></td>
			</tr>
			@endif
	@endif
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
