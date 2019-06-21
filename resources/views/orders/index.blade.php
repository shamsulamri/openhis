@extends('layouts.app2')

@section('content')	
<h3>Order List</h3>
<br>
@if ($orders->total()>0)
<table class="table table-condensed">
	<tbody>
<?php 
$ago='';
$category='';
?>
@foreach ($orders as $order)
	<?php $status='';?>

	@if ($order->order_is_discharge==1) 
			<?php $status='warning' ?>
	@endif
	@if ($order->order_completed==1) 
			<?php $status='success' ?>
	@endif
	<!--
	@if ($ago!=$dojo->diffForHumans($order->created_at))
	<tr class='info'>
		<td colspan=2>
			<small>
			<?php $ago =$dojo->diffForHumans($order->created_at); ?> 
			{{ $ago }}
			</small>	
		</td>
	</tr>
	@endif
	-->
	<!--
	@if ($category != $order->category_name)
	<tr bgcolor='#efefef'>
			<td colspan=2>
			{{ $order->category_name }}
			</td>
			<td>
			<div align='right'>
			U
			</div>
			</td>
			<td width='10'>
			</td>
	</tr>
		<?php $category=$order->category_name; ?>	
	@endif
	-->
	<tr>
			
			<td width=10>
				@if ($order->order_completed==1) <span class='label label-success'>@endif
					{{ $order->product_code }}
				@if ($order->order_completed==1) </span>@endif
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
					@if ($order->order_completed==1 && $order->post_id==0)
						<a href='{{ URL::to('orders/'. $order->order_id . '/edit') }}'>
					@else
						<a href='{{ URL::to('orders/'. $order->order_id . '/show') }}'>
					@endif
						{{ ucfirst(strtoupper($order->product_name)) }}
					</a>
			@endif
			<!-- Ordered by -->

			<!-- -->
			@if ($order->order_report)	
			&nbsp;
			<span class='fa fa-file-o'></span>
			@endif
			</td>
			<td width='10'>
				<div align='right'>
				{{ $order->order_completed?$order->order_quantity_supply:$order->order_quantity_request }}
				</div>
			</td>
			<td align='right'>
				@if ($order->user_id == Auth::user()->id && $order->post_id==0 && $order->order_completed==1)
					<a class='btn btn-danger btn-xs' href='{{ URL::to('orders/delete/'. $order->order_id) }}'>
							<span class='glyphicon glyphicon-minus'></span>
					</a>
				@endif
				@if ($order->post_id>0)
						@can('module-discharge')
							<a class='btn btn-danger btn-xs' href='{{ URL::to('orders/delete/'. $order->order_id) }}'>
									<span class='glyphicon glyphicon-minus'></span>
							</a>
						@endcan
				@endif
				@if ($order->order_completed==0) 
					@if ($order->post_id>0 && $order->order_is_discharge==0)
						@if (!isset($order->cancel_id))
							<a class='btn btn-warning btn-xs' href='{{ URL::to('/order_cancellations/create/'. $order->order_id) }}'>Cancel</a>
						@endif
					@else
						@if (!isset($order->cancel_id) && $order->user_id == Auth::user()->id)
							<a class='btn btn-danger btn-xs' href='{{ URL::to('orders/delete/'. $order->order_id) }}'>
									<span class='glyphicon glyphicon-minus'></span>
							</a>
						@else
								@if (!isset($order->cancel_id))
									<a class='btn btn-warning btn-xs' href='{{ URL::to('/order_cancellations/create/'. $order->order_id) }}'>
											<span class='glyphicon glyphicon-remove'></span>
									</a>
								@endif
						@endif
					@endif
				@else
					@if ($order->order_report != '')
					<!--
					<a class='btn btn-default btn-xs' href='{{ URL::to('orders/'. $order->order_id .'/show') }}'>View Report</a>
					-->
					@endif
				@endif
				@if (!empty($order->document_uuid))
					<a class='btn btn-primary btn-xs' href='{{ URL::to('documents/file/'. $order->document_uuid) }}'>View</a>
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
