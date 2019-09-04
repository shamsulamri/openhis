
	<!--
	<tr>
			<td colspan='9' height='1'>	
				<hr>
			</td>
	</tr>
	-->
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
	<tr class='{{ $status }}'>
			<td width='48' valign='top'>
					@if (!isset($order->cancel_id) && $order->order_completed==0)
						{{ Form::checkbox($order->order_id, 1, $order->order_completed,['class'=>'i-checks']) }}
					@endif
			</td>
			<td valign='top'>
					{{$order->product_code}}
			</td>
			<td valign='top'>
				@if ($order->order_completed==0)
					<a href='{{ URL::to('order_tasks/'. $order->order_id . '/edit') }}'>
					{{$order->product_name}}
					</a>
				@else
					{{$order->product_name}}
				@endif

					@if (!empty($order->order_description))
						<h5>
							{{ $order->order_description }}
						</h5>
					@endif

					@if ($status=='danger')
						<span class='label label-danger'>Insufficient supply.</span>
					@endif
			</td>
			<td>
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
				<!--
					{{ $order->name }}
					<br>
					{{ (DojoUtility::dateTimeReadFormat($order->consultation_date)) }}
				-->
			</td>
			<td>
				@if (!empty($order->updated_user))
					{{ $order->updated_user }}
					<br>
					{{ (DojoUtility::dateTimeReadFormat($order->completed_at)) }}
				@endif
			</td>
			<td align='right' valign='top'>
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
	<tr>
			<td></td>
			<td>Batch Number</td>
			<td>Expiry Date</td>
			<td>Available</td>
			<td>Quantity</td>
			<td></td>
	</tr>
@if ($order->order_completed==0)
	<tr>
		<td colspan='9'>	
		<table width='100%'>
		 <thead>
			<tr> 
			<th width='22'></th>
			<th width='150'><label>Batch Number</label></th>
			<th width='150'><div align='center'>Expiry Date</div></th>
			@if ($order->order_completed == 0) 
			<th width='150'><div align='center'>Available</div></th>
			@endif
			<th width='80'><div align='right'>Quantity</div></th>
			</tr>
		  </thead>
	<tbody>
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
	@else 
			@if ($order->order_completed == 0)
			<tr>
				<td></td>
				<td>N/A</td>
				<td align='center'>N/A</td>
				<td align='center'>
					@if ($order->product_stocked && $order->order_completed==0)
							{{ $on_hand - $allocated }}
					@else
							-
					@endif
				</td>
				<td align='center'>
					{{ Form::text('quantity_'.$order->order_id, $order->order_quantity_request, ['class'=>'form-control']) }}
				</td>
			</tr>
			@endif
	@endif
	<tr>
			<td colspan='9' height='1'>	
				<hr>
			</td>
	</tr>
	</tbody>
		</table>
		</td>
	</tr>
@endif
