@extends('layouts.app2')

@section('content')	
<h3>Order List</h3>
<form action='/order/index' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-default"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
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
	@if (empty($order->post_id))
			<?php $status='danger' ?>
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
			
			<td width=5>
			<!--
				@if ($order->order_completed==1) <span class='label label-success'>@endif
					{{ $order->product_code }}
				@if ($order->order_completed==1) </span>@endif
			-->
				<span class='label label-{{ $status }}'>
					&nbsp;
				</span>
			</td>
			<td>
			@if (isset($order->cancel_id)) 
					<a href='{{ URL::to('order_cancellations/'. $order->cancel_id) }}'>
					<strike>	{{ ucfirst(strtoupper($order->product_name)) }}</strike>
					</a>
			@elseif ($order->order_completed==0) 
					@if ($order->post_id>0)
						<!--
						<a href='{{ URL::to('orders/'. $order->order_id . '/show') }}'>
						-->
						<a href='{{ URL::to('orders/'. $order->order_id . '/edit') }}'>
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
			<small>
			@if (empty($order->bom_code))
			<br>
			{{ $order->product_code }}
			<!--
			(RM{{ number_format($order->order_unit_price*$order->order_quantity_request,2) }})
			-->
			@endif
			@if (!empty($order->bom_code))
				<br>
				{{ $order->bom->product_name }}
			@endif
			<!-- Ordered by -->
			@if (Auth::user()->authorization->module_discharge == 1) 
				<br>
				Ordered by {{ $order->user->name }}
				<br>
				{{ DojoUtility::dateTimeReadFormat($order->consultation_date) }} ({{ DojoUtility::diffForHumans($order->consultation_date) }})
			@else
					({{ DojoUtility::diffForHumans($order->consultation_date) }})
			@endif
			</small>
			</td>
			<td align='right'>
				@if ($order->order_report != '')
					@if ($order->product->category_code=='imaging')
					<a target="_blank" class='btn btn-default btn-xs' href="{{ Config::get('host.report_server')  }}/ReportServlet?report=order_report&id={{ $order->order_id }}">
						Report
					</a>
					@else
					<a class='btn btn-default btn-xs' href='{{ URL::to('orders/'. $order->order_id .'/show') }}'>Report</a>
					@endif
				@endif
				@if (!empty($order->document_uuid))
					<a class='btn btn-primary btn-xs' href='{{ URL::to('documents/file/'. $order->document_uuid) }}'><span class='glyphicon glyphicon-file' aria-hidden='true'></span>
</a>
				@endif
			</td>
			<td>
				<div align='right'>
				{{ $order->order_completed?$order->order_quantity_supply:$order->order_quantity_request }}
				</div>
			</td>
			<td align='right'>
				@if ($order->user_id == Auth::user()->id)
						@if ($order->post_id==0) 
								<a class='btn btn-danger btn-xs' href='{{ URL::to('orders/delete/'. $order->order_id) }}'>
									<span class='glyphicon glyphicon-minus'></span>
								</a>
						@else
								@if ($order->order_completed==0 && empty($order->cancel_id))
									<a class='btn btn-warning btn-xs' href='{{ URL::to('/order_cancellations/create/'. $order->order_id) }}'>
											<span class='glyphicon glyphicon-remove'></span>
									</a>
								@endif
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
