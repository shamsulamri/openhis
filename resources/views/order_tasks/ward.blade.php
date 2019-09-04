<style>
table, th, td, tr {
		border: 0px solid black;	
		padding: 2px;
}
tr.border_bottom td {
  border-bottom:1pt solid #EFEFEF;
}
</style>
@if ($order_tasks->total()>0)
<table width='100%'>
	<tbody>
@foreach ($order_tasks as $order)
	@if ($order->product_local_store==0)
			@if ($order->order_completed==0)
					@if ($order->order_is_discharge==0)
						@include('order_tasks.drugs')
						<?php
							$user_id = $order->user_id;
							$show_line = true;
							$new_line = true;
						?>
					@endif
			@endif
	@endif
@endforeach
</tbody>
</table>
@endif
