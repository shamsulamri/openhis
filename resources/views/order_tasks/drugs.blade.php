
		@if ($user_id != $order->user_id) 
			<?php $show_line = false; ?>

			@if ($new_line)
			<tr>
				<td><br></td>
			</tr>
			@endif
			<tr>
				<td bgcolor='#EFEFEF' colspan='12'>
					<h3>&nbsp;{{ $order->name }}, 
						<small>
							{{ (DojoUtility::dateTimeReadFormat($order->consultation_date)) }}
						</small>
						@if ($order->order_is_discharge)
							<div class='pull-right'>
								Discharge Orders &nbsp;
							</div>
						@endif
					</h3>
				</td>					
			</tr>
			<tr>
				<td height='10'></td>
			</tr>
		@endif

	<?php 
	$status='';
	$allocated =  $stock_helper->getStockAllocated($order->product_code, Auth::user()->defaultStore(), $encounter_id); //-$order->order_quantity_request;
	$on_hand = $stock_helper->getStockOnHand($order->product_code, Auth::user()->defaultStore())?:0;
	$available = $on_hand-$allocated;
	$batches = $stock_helper->getBatches($order->product_code, $order->order_id)?:null;
	$quantity_request = $order->order_quantity_request;
	?>
	@if ($order->product_stocked)
						@if ($on_hand-$allocated<$order->order_quantity_request)
								<?php $status = 'danger'; ?>
						@endif
	@endif
	@if ($show_line)
	<tr class='border_bottom'>
			<td colspan='11' height='10'>
			</td>
	</tr>
	<tr>
			<td colspan='11' height='10'>
			</td>
	</tr>
	@endif
	<tr class='{{ $status }}'>
			<td width='10' valign='top' colspan='1'>
					@if (!isset($order->cancel_id) && $order->order_completed==0)
						{{ Form::checkbox($order->order_id, 1, $order->order_completed,['class'=>'i-checks']) }}
					@else
						<a href='/order_task/reopen/{{ $order->order_id }}' class='btn btn-danger btn-xs'>Reopen</a>
					@endif
			</td>
			<td valign='top' colspan='1'>
					{{$order->product_code}}
			</td>
			<td valign='top' colspan='5'>
				@if ($order->order_completed==0)
					<a href='{{ URL::to('order_tasks/'. $order->order_id . '/edit') }}'>
					{{$order->product_name}}
					</a>
				@else
					{{$order->product_name}}
				@endif

				@if ($order->order_completed==1)
					@if ($order->category_code=='drugs' | $order->category_code=='drug_generics')
						<br><strong>
						{{ $order_helper->getPrescription($order->order_id) }}
						</strong>
					@endif
				@endif
			</td>
			@if ($order->order_completed==0)
			<td colspan='3' valign='top'>
					@if ($status=='danger')
						<span class='label label-danger'>Insufficient supply.</span>
					@endif
			</td>
			@else
			<td colspan='1' valign='top'>
						{{ $order->order_quantity_supply }} Units.
			</td>
			<td colspan='1' valign='top'>
					@if ($order->product_local_store==0)
					Prepared by {{ $order->updated_user }}
					<br>
					{{ (DojoUtility::dateTimeReadFormat($order->completed_at)) }}
					@endif
			</td>
			<td colspan='1' valign='top'>
					@if (!empty($order->dispensed_by))
					Dispensed by {{ $order->dispensed_user }}
					<br>
					{{ (DojoUtility::dateTimeReadFormat($order->dispensed_at)) }}
					@endif
			</td>
			@endif
			<td colspan='2' align='right' valign='top' width='100'>
					@if (!isset($order->cancel_id))
					@endif
					@if ($order->order_completed==0 && !isset($order->cancel_id))
					<a class='btn btn-warning btn-xs' href='{{ URL::to('/task_cancellations/create/'. $order->order_id) }}'>Cancel</a>
					@endif

					@if ($order->order_completed==1)
							@if (empty($order->dispensed_by))
								{{ Form::checkbox('dispense_'.$order->order_id, 1, null,['class'=>'i-checks']) }}
							@endif
					@endif

			</td>
	</tr>
@if ($order->order_completed==0)
	<tr>
			<td></td>
			<td></td>
			<td colspan='5'>
					@if ($order->category_code=='drugs' | $order->category_code=='drug_generics')
						<strong>
						{{ $order_helper->getPrescription($order->order_id) }}
							@if ($order->order_completed==1)
							<div class='pull-right'>
							{{ $order->order_quantity_supply }} Units
							</div>
							@endif
						</strong>
					@endif
					@if (!empty($order->order_description))
						<h5>
							{{ $order->order_description }}
						</h5>
					@endif

			</td>
			<td colspan='1' valign='bottom'><strong>Batch Number</strong></td>
			<td colspan='1' valign='bottom' align='center'><strong>Expiry Date</strong></td>
			<td colspan='1' valign='bottom' align='center'><strong>Available</strong></td>
			<td colspan='1' valign='bottom' width='10%' align='center'><strong>Unit</strong></td>
			<td colspan='1' valign='bottom' width='10%' align='right'><strong>Quantity</strong></td>
	</tr>
	@if ($batches->count()>0 && $order->order_completed==0)
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
				<td colspan='7'></td>
				<td colspan='1'>
					{{ $batch->inv_batch_number }}
				</td>
				<td colspan='1' align='center'>
					{{ DojoUtility::dateReadFormat($batch->batch_expiry_date) }}
				</td>
				@if ($order->order_completed == 0) 
				<td colspan='1' align='center'>
					{{ $batch->sum_quantity }}
				</td>
				@endif
				<td colspan='1' align='center'>
					@if ($uom = $order_helper->getDefaultPrice($order->product_code))
					{{ $uom->unitMeasure->unit_name }}
					@endif
				</td>	
				<td colspan='1'>
					@if ($order->order_completed == 0) 
            		{{ Form::text('batch_'.$order->order_id.'_'.$batch->product_code.'_'.$batch->batch_id, $supply?:0, ['class'=>'form-control']) }}
					@else
            		{{ Form::label('batch_'.$order->order_id.'_'.$batch->product_code.'_'.$batch->batch_id, abs($supply?:0), ['class'=>'form-control']) }}
					@endif
				</td>
			</tr>
		@endforeach
		@if ($errors->has('batch_'.$batch->product_code) | $total_supply==0) 
		<tr>
			<td colspan=9>
			</td>
			<td>
					<div align='center' class='has-error'>
            		<p class="help-block">{{ $errors->first('batch_'.$batch->product_code)?:"Sum cannot be zero" }}</p>
					</div>
			</td>
		</tr>
		@endif
	@else 
			@if ($order->order_completed == 0)
			<tr>
				<td colspan='6'></td>
				<td colspan='1'>N/A</td>
				<td colspan='1' align='center'>N/A</td>
				<td colspan='1' align='center'>
					@if ($order->product_stocked && $order->order_completed==0)
							{{ $on_hand - $allocated }}
					@else
							-
					@endif
				</td>
				<td colspan='1' align='center'>
					@if ($uom = $order_helper->getDefaultPrice($order->product_code))
					{{ $uom->unitMeasure->unit_name }}
					@endif
				</td>	
				<td colspan='1' align='center'>
					{{ Form::text('quantity_'.$order->order_id, $order->order_quantity_request, ['class'=>'form-control']) }}
				</td>
			</tr>
			@endif
	@endif
@endif
