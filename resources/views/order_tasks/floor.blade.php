@if ($order_tasks->total()>0)
<table width='100%'>
<tbody>
@foreach ($order_tasks as $order)
	@if ($order->product_local_store==1)
			@include('order_tasks.drugs')
				<?php
					$user_id = $order->user_id;
					$show_line = true;
					$new_line = true;
				?>
	@endif
@endforeach
</tbody>
</table>
@endif
