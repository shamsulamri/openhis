@if ($order_tasks->total()>0)
<table width='100%'>
<tbody>
@foreach ($order_tasks as $order)
	@if (!empty($order->stop_id))
						@include('order_tasks.drugs')
						<?php
							$user_id = $order->user_id;
							$show_line = true;
						?>
	@endif
@endforeach
@endif
</tbody>
</table>
