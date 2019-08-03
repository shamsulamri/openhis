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
			<div class="col-xs-4">
					<button class="btn btn-primary" type="submit" value="Submit">Update Status</button>
					@can('module-order')
					<!--
					<a class='btn btn-primary' href='/orders/make'>Edit Orders</a>
					-->
					@endcan
			</div>
			<div align="right" class="col-xs-8">
@if (Auth::user()->author_id == 5)
					<a class="btn btn-primary" href="{{ Config::get('host.report_server') }}/ReportServlet?report=order_labels&id={{ $encounter->encounter_id }}" target="_blank" role="button"><span class='glyphicon glyphicon-print'></span>
 Lab Label</a>
@endif
@if (Auth::user()->author_id == 15)
					<a class="btn btn-primary" href="{{ Config::get('host.report_server') }}/ReportServlet?report=request_form&id={{ $encounter->encounter_id }}" target="_blank" role="button"><span class='glyphicon glyphicon-print'></span>
 Request Form</a>
@endif
@if (Auth::user()->author_id == 13)
					<a class="btn btn-primary" href="{{ Config::get('host.report_server') }}/ReportServlet?report=drug_label&id={{ $encounter->encounter_id }}" target="_blank" role="button"><span class='glyphicon glyphicon-print'></span>
 Drug Label</a>
					<a class="btn btn-primary" href="{{ Config::get('host.report_server') }}/ReportServlet?report=drug_prescription&id={{ $encounter->encounter_id }}" target="_blank" role="button"><span class='glyphicon glyphicon-print'></span>
 Prescription</a>
					<a class="btn btn-primary" href="{{ Config::get('host.report_server') }}/ReportServlet?report=drug_discharge&id={{ $encounter->encounter_id }}" target="_blank" role="button"><span class='glyphicon glyphicon-print'></span>
 Discharge</a>
@endif
			</div>
	</div>
	<br>
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
    <th>Order By</th>
    <th>Completed By</th>
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($order_tasks as $order)
	<?php 
	$status='';
	$allocated =  $stock_helper->getStockAllocated($order->product_code, Auth::user()->defaultStore(), $encounter_id); //-$order->order_quantity_request;
	$on_hand = $stock_helper->getStockOnHand($order->product_code, Auth::user()->defaultStore())?:0;
	$available = $on_hand-$allocated;
	$batches = $stock_helper->getBatches($order->product_code, $order->order_id)?:null;
	$quantity_request = $order->order_quantity_request;
	$dischargeColor = '#ebffd9';
	?>
	@if ($order->product_stocked)
						@if ($on_hand-$allocated<$order->order_quantity_request)
								<?php $status = 'danger'; ?>
						@endif
	@endif
	@if ($order->order_is_discharge==1)
	<tr class='{{ $status }}' bgcolor='{{ $dischargeColor }}'>
	@else
	<tr class='{{ $status }}'>
	@endif
			<td width='48'>
					@if (!isset($order->cancel_id) && $order->order_completed==0)
						{{ Form::checkbox($order->order_id, 1, $order->order_completed,['class'=>'i-checks']) }}
					@else
						<span class="fa fa-check-square-o" aria-hidden="true"></span>
					@endif
			</td>
			<td>
					<a href='{{ URL::to('order_tasks/'. $order->order_id . '/edit') }}'>
					{{$order->product_name}}
					</a>
					<br>{{$order->product_code}}

					@if ($order->category_code=='drugs' | $order->category_code=='drug_generics')
						<h3>
						{{ $order_helper->getPrescription($order->order_id) }}
						</h3>
					@endif

					@if ($order->order_is_discharge==1)
						<span class='label label-success'>Discharge Order</span>
					@endif

					@if ($status=='danger')
						<span class='label label-danger'>Insufficient supply.</span>
					@endif
			</td>
			<td>
			@if ($order->product_stocked && $order->order_completed==0)
					{{ $on_hand }} 
			@else
				-
			@endif
			</td>
			<td>
			@if ($order->product_stocked && $order->order_completed==0)
					{{ $allocated }} 
			@else
				-
			@endif
			</td>
			<td>
			@if ($order->product_stocked && $order->order_completed==0)
					{{ $on_hand - $allocated }}
			@else
					-
			@endif
			</td>
			<td width='100'>
			@if ($status=='danger')
				<div class='has-error'>	
			@endif
				@if ($order->product_stocked==1)
						@if ($order->order_completed==0 && $batches->count()==0) 
							<div align='center' class='@if ($errors->has('quantity_'.$order->order_id) | $order->order_quantity_request==0) has-error @endif'>
							{{ Form::text('quantity_'.$order->order_id, $order->order_quantity_request, ['class'=>'form-control']) }}
            				@if ($errors->has('quantity_'.$order->order_id)) <p class="help-block">{{ $errors->first('quantity_'.$order->order_id) }}</p> @endif
							</div>
						@else
							{{ $order->order_quantity_supply }}
							{{ Form::hidden('quantity_'.$order->order_id, $order->order_quantity_request) }}
						@endif
				@else
						{{ $order->order_quantity_supply }} 
						@if ($order->product_stocked && $order->order_completed==0)
								{{ Form::hidden('quantity_'.$order->order_id, $order->order_quantity_supply) }}
						@endif
				@endif 
			@if ($status=='danger')
				</div>	
			@endif
			</td>
			<td>
					{{ $order->name }}
					<!--
					<br>
					{{ $order->ward_name}}
					-->
					<br>
					{{ (DojoUtility::dateTimeReadFormat($order->consultation_date)) }}
			</td>
			<td>
					{{ $order->updated_user }}
					<br>
					{{ (DojoUtility::dateTimeReadFormat($order->completed_at)) }}
			</td>
			<td align='right'>
					@if (!isset($order->cancel_id))
					@endif
					@if ($order->order_completed==0 && !isset($order->cancel_id))
					<a class='btn btn-warning btn-xs' href='{{ URL::to('/task_cancellations/create/'. $order->order_id) }}'>Cancel</a>
					@endif

					@if ($order->order_completed==1)
							@if (empty($order->dispensed_by))
								{{ Form::checkbox('dispense_'.$order->order_id, 1, null,['class'=>'i-checks']) }}
							@else
								<span class="fa fa-check-square-o" aria-hidden="true"></span>
							@endif
					@endif

			</td>
	</tr>
	@if ($batches->count()>0 && $order->order_completed==0)

	@if ($order->order_is_discharge==1)
	<tr bgcolor='{{ $dischargeColor }}'>
	@else
	<tr>
	@endif
			<td colspan='9'>	
		<table>
		 <thead>
			<tr> 
			<th width='40'></th>
			<th width='150'><label>Batch Number</label></th>
			<th width='150'><div align='center'>Expiry Date</div></th>
			@if ($order->order_completed == 0) 
			<th width='150'><div align='center'>Available</div></th>
			@endif
			<th width='80'><div align='center'>Quantity</div></th>
			</tr>
		  </thead>
	<tbody>
<?php 
		$total_supply = 0;
?>
		@foreach ($batches as $batch)
			<?php
			$supply = 0;
			if ($batch->sum_quantity<$quantity_request) {
				$supply = $batch->sum_quantity;
				$quantity_request -= $batch->sum_quantity;
			} else {
				$supply = $quantity_request;
				$quantity_request = $quantity_request-$supply;
			}
			$total_supply += $supply;
			?>
			<tr>
				<td>
				</td>
				<td>
					{{ $batch->inv_batch_number }}
				</td>
				<td align='center'>
					{{ DojoUtility::dateReadFormat($batch->batch_expiry_date) }}
				</td>
				@if ($order->order_completed == 0) 
				<td align='center'>
					{{ $batch->sum_quantity }}
				</td>
				@endif
				<td>
					@if ($order->order_completed == 0) 
            		{{ Form::text('batch_'.$batch->product_code.'_'.$batch->batch_id, $supply?:0, ['class'=>'form-control']) }}
					@else
            		{{ Form::label('batch_'.$batch->product_code.'_'.$batch->batch_id, abs($supply?:0), ['class'=>'form-control']) }}
					@endif
				</td>
				
			</tr>
		@endforeach
		@if ($errors->has('batch_'.$batch->product_code) | $total_supply==0) 
		<tr>
			<td colspan=4>
			</td>
			<td>
					<div align='center' class='has-error'>
            		<p class="help-block">{{ $errors->first('batch_'.$batch->product_code)?:"Sum cannot be zero" }}</p>
					</div>
			</td>
		</tr>
		@endif
	</tbody>
		</table>
			</td>
	</tr>
	@endif
@endforeach
@endif
</tbody>
</table>
	{{ Form::hidden('ids', $ids) }}
	{{ Form::hidden('dispense_ids', $dispense_ids) }}
	{{ Form::hidden('encounter_id', $encounter_id) }}
	<button class="btn btn-primary" type="submit" value="Submit">Update Status</button>
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
