@if ($order_tasks->total()>0)
<table width='100%'>
<tbody>
@foreach ($order_tasks as $order)
	@if ($order->order_is_discharge==1)
			@if ($order->order_completed==0)
						@include('order_tasks.drugs')
						<?php
							$user_id = $order->user_id;
							$show_line = true;
						?>
			@endif
	@endif
@endforeach
@endif
</tbody>
</table>
